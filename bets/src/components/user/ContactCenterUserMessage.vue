<template>
  <!-- v-if="!currentHash || currentHash === hash" -->
  <div 
    :class="`padding_vertical-bet-tablet userMessagesRow userMessagesGrid userMessagesGrid_row  display_grid v-centered height_fill bordered-bottom bordered-bottom_gray someBetContainer_tablet padding-bottom_base-phone-rem ${className}`"
    role="row"
    tabindex="0"
    @click="threadDisplayStateHandler($store.state.personalRoom.user.choosenThread.isOpened ? 'close' : 'open')()"
  >
    
    <!-- begin userMessagesGridId -->
    <span 
      role="cell" 
      scoped="row"
      class="userMessagesGridId">
      {{ threadId }}
    </span>
    <!-- end userMessagesGridId -->

    <!-- begin userMessagesGridTitle -->
    <h3 
      role="cell" 
      class="userMessagesGridTitle font-weight_semibold font-size_base">
      {{ title }}
    </h3>
    <!-- end userMessagesGridTitle -->

    <!-- begin userMessagesGridLastAnswerDate -->
    <time
      :datetime="lastAnswerDate | formatDate()"
      role="cell" 
      class="userMessagesGridLastAnswerDate color_paleBlue">
      {{ lastAnswerDate | formatDate("DD MMMM YYYYг, HH:mm") }}
    </time>
    <!-- end userMessagesGridLastAnswerDate -->

   
 
    <!-- begin userMessagesGridStatus -->
    <p
      role="cell"
      class="userMessagesGridStatus  margin-top_zero">
      {{ statusMessage }} 
      <span class="userMessagesGridStatus__name color_blue font-weight_semibold">
        {{ statusName }}
      </span>
    </p>
    <!-- end userMessagesGridStatus -->

    <!-- begin userMessagesGridActions -->
    <div 
      role="cell"
      class="userMessagesGridActions parent padding-top_small-tablet h-end">
      <check-button 
        :action="acceptMessage" 
        :is-standart-proportion="false"
        class-name="userMessagesGridActions__button
        userMessagesGridActions__button_check color_green"
        label="Отметить сообщение, как решённое"
        modifier="white"
        micro-action
      />
      

      <base-button 
        :action="deleteMessage"
        class-name="userMessagesGridActions__button"
        label="Удалить сообщение"
        micro-action
      >
        <lazy-image 
          :fit-block="false"
          :src="cancelIcon"
        />
      </base-button>
    </div>
    <!-- end userMessagesGridActions -->
  </div>
</template>

<script>
import { cancel } from "@/assets/images/icons";
import { requestUrls } from "@/constants/conf";
import { ID } from "@/constants/pureFunctions";

import CheckButton from "@/components/CheckButton";

export default {
  name: "ContactCenterUserMessage",
  components: {
    CheckButton
  },
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    threadId: {
      type: [String, Number],
      required: true
    },
    title: {
      type: String,
      required: true
    },
    lastAnswerDate: {
      type: [String, Date],
      required: true
    },
    statusMessage: {
      type: String,
      required: true
    },
    statusName: {
      type: String,
      required: true
    },
    definedHash: {
      type: String,
      required: false,
      default: ""
    }
  },
  data: () => ({
    cancelIcon: cancel
  }),
  computed: {
    // Генерирую уникальный идентификатор.
    hash() {
      return this.definedHash || ID();
    }
  },

  methods: {
    composePostOptions({ url, data }) {
      return {
        method: "post",
        headers: {
          "X-CSRFToken": this.$store.state.csrftoken
        },
        url,
        data
      };
    },
    acceptMessage() {
      const data = {
        threadId: this.threadId
      };

      // POST запрос для пометки сообщения, как решённого.
      axios(
        this.composePostOptions({
          url: requestUrls.acceptMessage,
          data
        })
      ).then(response => {});
    },
    deleteMessage() {
      const data = {
        threadId: this.threadId
      };

      // POST запрос для удаления сообщения.
      axios(
        this.composePostOptions({
          url: requestUrls.deleteMessage,

          data
        })
      ).then(response => {});
    },

    openThread() {
      const threadId = this.threadId;
      this.$store.dispatch("personalRoom/openChoosenThread", {
        hash: this.hash,
        threadData: {
          title: this.title,
          lastAnswerDate: this.lastAnswerDate,
          statusName: this.statusName,
          status: {
            name: this.statusName,
            message: this.statusMessage
          },
          threadId
        },
        threadId
      });
    },
    closeThread() {
      this.$store.dispatch("personalRoom/closeThreadAndCleanPagination");
    },
    // Меняет состояние треда в зависимости от действия.
    threadDisplayStateHandler(action) {
      return () => {
        switch (action) {
          case "open":
            this.openThread();
            break;

          case "close":
            this.closeThread();
            break;

          default:
        }
      };
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'

.userMessagesRow
  transition: background-color .2s $sharp

  &:hover, &:focus
  	background-color: #f4f3f3
  

.userMessagesGrid
	
	&Id
		font-size: em(10, 12)


	&LastAnswerDate
    	padding-left: 2rem
    	padding-right: 1.5rem

	&Actions


	&Status

    @include breakpoint($someBetDesktop)
		  padding-left: .66rem

		
.userMessagesGridActions

	&__button
		font-size: em(13, 12)

</style>
