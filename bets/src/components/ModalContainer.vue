<template>
  <transition
    name="fading"
    appear>
    <div
      v-if="isShown"
      :class="`popup position_base fixed parent h-centered borderBox${containerClass ? ' '+containerClass : ''}`"
      role="presentation"
    >
      <!-- begin litter -->
      <section
        :class="{
          'litter relative background-color_white shadow_spread': true,
          [className]: className,
          [`litter_${modifier}`]: modifier,
          litter_normal: !smallLitter,
          litter_small: smallLitter,
          litter_fitContent: isLitterFitContent
        }"
      >
        <slot />
        <base-button
          :action="onCloseModal"
          :class-name="closeButtonClasses + ` relative`"
          unstyled>
          <icon-close />
        </base-button>
      </section>
      <!-- end litter -->
    </div>
  </transition>
</template>

<script>
export default {
  name: "ModalContainer",
  props: {
    onCloseModal: {
      type: Function,
      required: true
    },
    isShown: {
      type: Boolean,
      required: false,
      default: true
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    className: {
      type: String,
      required: false,
      default: ""
    },
    containerClass: {
      type: String,
      required: false,
      default: ""
    },
    isBigCloseButton: {
      type: Boolean,
      required: false,
      default: false
    },
    closeButtonModifier: {
      type: String,
      required: false,
      default: "small"
    },
    smallLitter: {
      type: Boolean,
      required: false,
      default: false
    },
    onOpen: {
      type: Function,
      required: false,
      default: () => {}
    },
    closeModal: {
      type: [Function, Boolean],
      required: false,
      default: false
    },
    isLitterFitContent: {
      type: Boolean,
      required: false,
      default: true
    },
    isDataUpdated: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  computed: {
    wrappers() {
      return {
        $root: document.documentElement,
        $body: document.body,
        $layouts: document.querySelector(".layouts")
      };
    },

    closeButtonClasses() {
      let buttonClasses = "absolute litter__closeButton index_big";
      const modifier = this.closeButtonModifier;
      const isBigCloseButton = this.isBigCloseButton;

      if (isBigCloseButton) {
        buttonClasses += " litter__closeButton_big";
      }

      if (modifier && !(isBigCloseButton && modifier === "small")) {
        buttonClasses += ` litter__closeButton_${modifier}`;
      }

      return buttonClasses;
    }
  },

  beforeMount() {
    if (!this.isDataUpdated) {
      this.onOpen();
    }
  },

  mounted() {
    this.preventBodyScroll();
  },

  destroyed() {
    const isAllowToScrollRoot = () => {
      const popups = Object.values(this.$store.state.popups);
      const isOneOfPopupsOpened = popups.some(isPopupOpened);

      function isPopupOpened(popupState) {
        let isOpened = false;

        if (typeof popupState === "boolean") {
          isOpened = popupState;
        }

        return isOpened;
      }

      return !isOneOfPopupsOpened;
    };

    if (isAllowToScrollRoot()) {
      this.styleWrappers("");
    }

    if (this.closeModal) {
      this.closeModal();
    }
  },

  methods: {
    preventBodyScroll() {
      const preventScrollStyles =
        "overflow:hidden;pointer-events:none;height:100%;";

      this.styleWrappers(preventScrollStyles);
    },

    styleWrappers(style) {
      const wrappers = this.wrappers;

      for (const $wrapper of Object.values(wrappers)) {
        $wrapper.setAttribute("style", style);
      }
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'
@import '../assets/sass/conf/_easing.sass'

.popup
  min-width: 100vw $i
  min-height: 100vh
  background-color: rgba($darkGray, .5)
  z-index: 100
  overflow-y: auto
  height: 1px
  pointer-events: auto

.litter
  transition-duration: 300ms
  transition-timing-function: $standart

  &_big
    min-width: em(1022)

  &_fitContent
    height: fit-content

  @include breakpoint($tablet-less)
    max-width: 90vw
    min-width: 85vw


  &__closeButton
    transition: color .2s $standart

    &:hover, &:focus

      @include breakpoint($tablet)
        color: rgba($white, 1)

    &:after
      content: ""
      position: absolute
      top: 6px
      right: -2px
      border-radius: 25%
      background-color: rgba(0,0,0, .15)
      height: 75%
      width: 75%
      filter: blur(3px)
      z-index: -1

      @include breakpoint($tablet-less)
        display: none

    top: 0

    &_big
      font-size: 2em
      right: em(-49, 32)

    &_normal
      font-size: 1.5em
      right: -1.5em

    &_small
      font-size: em(18, 16  )
      right: em(-30)

    @include breakpoint($tablet)
      color: rgba($white, .9)

    @include breakpoint($tablet-less)
      right: .5rem
      color: $darkGray
      top: .5rem

</style>
