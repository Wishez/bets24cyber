<template>
  <!-- begin stream -->
  <fade-translate-transition-group
    :class-name="`streams margin-bottom_base-phone margin_centered-phone parent column  ${className}`"
    tag="div"
  >
    <!-- begin streamChoice -->
    <div
      v-if="isStreamInited"
      :key="0"
      :data-index="0"
      class="streamChoice width_fill relative index_big parent row shadow_spread margin-bottom_small margin-top_small background-color_white">
      <base-button
        :action="switchLanguagesListState"
        :label="(isLanguagesListOpened ? 'Закрыть' : 'Открыть') + ' список языков'"
        class-name="baseChild langaugeChoiceButton parent centered"
        unstyled
      >
        <span 
          v-if="currentLanguage.name" 
          class="parent parent_row streamChoice__name font-weight_semibold baseChild h-between margin-right_small centered">
          <lazy-image 
            :src="currentLanguage.image" 
            class-name="languagesList__image margin-right_small"
          />

          {{ currentLanguage.name }}
        </span>

        <chevron-icon
          :position="isLanguagesListOpened ? 'up' : 'down'"
        />
      </base-button>

      <transition
        appear
        name="fadeTranslateToBottom">
        <ul
          v-if="isLanguagesListOpened"
          :aria-expended="isStreamChoicesOpened"
          class="languagesList popupList absolute"
        >
          <!-- begin streamName -->
          <li
            v-for="(language, index) in languages"
            :key="index"
            class="parent parent_row">
            <base-button
              :action="selectLanguage(language)"
              class-name="parent parent_row centered"
              label="Выбрать язык"
              unstyled
            >
              <lazy-image 
                :src="language.image" 
                class-name="languagesList__image margin-right_small"/>
              
              <span class-name="languagesList__name">{{ language.name }}</span>
            </base-button>

          </li>
          <!-- end streamName -->
        </ul>
      </transition>
      <!-- end streamList -->
      
      <span class="streamChoice__name font-weight_semibold baseChild h-between">
        {{ !selectedStream ? 'Выбрать стрим' : selectedStream.name }}
      </span>
      <base-button
        :action="switchListChoicesState"
        :label="(isStreamChoicesOpened ? 'Закрыть' : 'Открыть') + ' список стримов'"
        class-name="baseChild streamChoice__button"
        unstyled
      >
        <chevron-icon
          :position="isStreamChoicesOpened ? 'up' : 'down'"
        />
      </base-button>
      <!-- begin streamList -->
      <transition
        appear
        name="fadeTranslateToBottom">
        <ul
          v-if="isStreamChoicesOpened"
          :aria-expended="isStreamChoicesOpened"
          class="streamsList popupList absolute"
        >
          <!-- begin streamName -->
          <li
            v-for="(channel, index) in filteredChannels"
            :key="index"
            class="streamName">
            <base-button
              :action="selectStream(channel)"
              label="Выбрать стрим"
              unstyled
            >
              {{ channel.name }}
            </base-button>

          </li>
          <!-- end streamName -->
        </ul>

      </transition>
      <!-- end streamList -->
    </div>
    <!-- end streamChoice -->
    <!-- begin streamFrame -->
    <div
      v-if="isStreamInited"
      :key="1"
      :data-index="1"
      class="streamFrame">
      <iframe
        id="matchStreamFrame"
        :src="selectedStreamUrl"
        height="168"
        class="width_fill-phone"
        width="229"
        frameborder="0"
        scrolling="no"
        allowfullscreen="true"/>
    </div>
    <!-- end streamFrame -->
  </fade-translate-transition-group>
  <!-- end stream -->
</template>

<script>
import { timeout } from "@/constants/pureFunctions";

export default {
  name: "MatchStreams",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    channels: {
      type: Array,
      required: true,
      validator: function(channels) {
        function isString(string) {
          return typeof string === "string";
        }
        return channels.every(
          channel => isString(channel.name) && isString(channel.lang)
        );
      }
    },

    languages: {
      type: Array,
      required: true,
      validator: function(languages) {
        function assert(value, type) {
          return typeof value === type;
        }

        return languages.every(
          language =>
            assert(language, "object") &&
            assert(language.name, "string") &&
            assert(language.image, "string")
        );
      }
    }
  },

  data: () => ({
    isStreamChoicesOpened: false,
    selectedStreamUrl: "",
    twitchUrl: "https://player.twitch.tv/?autoplay=false&channel=",
    selectedStream: false,
    isStreamInited: false,
    isLanguagesListOpened: false,
    currentLanguage: {
      image: "",
      name: ""
    },
    filteredChannels: []
  }),

  mounted() {
    this.$nextTick(() => {
      if (this.languages.length) {
        this.currentLanguage = this.languages[0];
        this.filterChannels();
      }

      timeout(() => {
        this.$set(this, "isStreamInited", true);

        this.setStreamUrl(this.channels[0]);
      }, 600);
    });
  },

  methods: {
    filterChannels() {
      this.filteredChannels = this.channels.filter(
        channel =>
          this.currentLanguage.name.toLowerCase() === channel.lang.toLowerCase()
      );
    },
    switchListChoicesState() {
      this.$set(this, "isStreamChoicesOpened", !this.isStreamChoicesOpened);
    },

    switchLanguagesListState() {
      this.$set(this, "isLanguagesListOpened", !this.isLanguagesListOpened);
    },

    setStreamUrl(streamName) {
      this.$set(this, "selectedStreamUrl", this.twitchUrl + streamName);
    },

    selectStream(streamName) {
      return () => {
        this.setStreamUrl(streamName);

        this.$set(this, "selectedStream", streamName);

        this.switchListChoicesState();
      };
    },

    selectLanguage(language) {
      return () => {
        this.currentLanguage = language;
        this.filterChannels();

        this.switchLanguagesListState();
      };
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'
.streams
  min-width: em(230, 12)

  @include breakpoint($phone-less)
      margin-top: 1.5em

.popupMatchStreams
  @include breakpoint($tablet-less)
      justify-content: center
.streamsList
  transform: translateY(-2px)
  border-top-right-radius: 0
  border-top-left-radius: 0

.streamChoice
	&__name
		padding: em(5) 0 em(5) em(11)
	&__button
		max-width: em(34, 12)
.streamFrame > iframe
    max-width: em(230, 12)
    max-height: em(126, 12)

.langaugeChoiceButton
  max-width: 81px
  padding-right: .6em
  border-right: 1px solid #ccc
  min-width: 60px

.languagesList
  max-width: 60px
  min-width: 75px

  &__name
  &__image
    width: 20px
    // margin-right: .25em
</style>
