<template>
  <base-button
    :action="action"
    :label="label"
    :class="{ 
      'circle parent centered color_white': true,
      'checkButton margin_vertical-littleOffsets margin-top_quarter margin-bottom_quarter': isStandartProportion,
      [className]: className
    }"
    :modifier="modifier"
    :micro-action="microAction"
    unstyled>
    <svg 
      width="1em" 
      height="1em" 
      viewBox="0 0 20 16"
      aria-hidden="true"
      role="img"
      class="checkButton__icon"
    >
      <g 
        stroke="currentColor" 
        stroke-width="1.5" 
        stroke-linecap="round" 
        stroke-linejoin="round" 
        fill="none" 
        fill-rule="evenodd">
        <path d="M5,9 L10,13.5" />
        <path d="M10,13.5 L18,4.5" />
      </g>
    </svg>
  </base-button>
</template>

<script>
export default {
  name: "CheckButton",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    modifier: {
      type: String,
      required: false,
      default: "blue"
    },
    action: {
      type: Function,
      required: true
    },
    label: {
      type: String,
      required: true
    },
    microAction: {
      type: Boolean,
      required: false,
      default: false
    },
    isStandartProportion: {
      type: Boolean,
      required: false,
      default: true
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'
@import '../assets/sass/conf/_easing.sass'


.checkButton
  height: em(21)
  width: em(21)

  &__icon
    transform: translateX(-1px)

</style>
