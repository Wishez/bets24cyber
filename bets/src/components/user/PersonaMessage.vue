<template>
  <article 
    :class="{
      'personaMessage parent_inline row  margin-top_increased wrap': true, 
      'personaMessage_user margin-left_auto': isUser, 
      'personaMessage_сompanion': isUser, 
      [className]: className
  }">
    <h2 
      :class="{
        'personaMessage__title font-size_base font-weight_semibold padding-top_small margin-bottom_base-phone': true,
        [`order_last personaMessage__title_user text_right-phone`]: isUser 
    }">
      {{ isUser ? $store.state.personalRoom.user.name : companionName }}
    </h2>
    <div 
      :class="{
        'grow parent column personaMessage__paragraph margin-top_zero background-color_lightGray messageLitter messageLitter_bloated': true,
        'personaMessage__paragraph_flag-left margin-left_auto-phone': isUser,
        'personaMessage__paragraph_flag-right': !isUser,
    }">
      <time 
        :datetime="date | formatDate()"
        class="width_fill personaMessage__date color_paleBlue"
      >
        {{ date | formatDate("DD MMMM YYYYг. HH:mm") }}
      </time>

      <p class="margin-top_small ">
        {{ message }}
      </p>
      <transition-group 
        v-if="images" 
        tag="ul" 
        name="fading" 
        class="personaMessageImages parent row width_fill index_big v-end display_grid ">
        <li 
          v-for="(image, index) in images" 
          :key="index" 
          class="personaMessageImages__image index_big">
          

          <lazy-image
            v-img="{
              src: image.url
            }"
            :src="image.url" 
            :fit-block="false" />
        </li >
      </transition-group>
    </div>
  </article>
</template>

<script>
import { ID } from "@/constants/pureFunctions";

export default {
  name: "PersonaMessage",
  components: {},
  mixins: [],
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    isUser: {
      type: Boolean,
      required: true
    },
    date: {
      type: [String, Date],
      required: true
    },
    message: {
      type: String,
      required: true
    },
    companionName: {
      type: String,
      required: true
    },
    images: {
      type: Array,
      required: true
    }
  },
  data: () => ({}),
  computed: {
    imagesGroupId() {
      if (this.images.length) {
        return `imagesGroupId_${ID()}`;
      } else return "";
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'

.personaMessage

  &_user 
  &__title

    &_user

      @include breakpoint($phone-less)
        min-width: 100%
        order: -1

  &Images 
      grid-template-columns: repeat(auto-fit, minmax(50px, 1fr))
      grid-gap: 10px

  &__date
    font-size: em(10, 12)

  &__paragraph
    max-width: em(305, 12)
    
    
    &_flag

      &-left

        @include breakpoint($phone)
          margin-right: 2.1rem

        &:after, &:before
          left: 99%


        &:before
          transform: translate(3px, 1.5px) scale(1.01)

      &-right

        @include breakpoint($phone)
          margin-left: 2.1rem

        &:after, &:before
          right: 99%

        &:after
          transform: rotate(180deg)

        &:before
          transform: rotate(180deg) translate(3px, -1.5px)

        


    &:after, &:before
      content: ""
      position: absolute
      clip-path: polygon(100% 50%, 0 0, 0 100%)
      height: 21px
      width: 21px
      top: em(22 / 100 * 25)

      @include breakpoint($phone-less)
        display: none

    &:after
      background-color: $lightGray
      z-index: 2

    &:before
      background-color: rgba(0,0,0,.1)
      filter: blur(4px)
      z-index: 1 
      


  &_user


</style>
