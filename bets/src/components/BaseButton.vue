<template>
  <button
    :class="{
      'unstyledButton': unstyled,
      'button round parent centered': !unstyled,
      [`button_${modifier}`] : modifier,
      [className]: className,
      button_pagination: pagination,
      button_microAction: microAction,
      button_circle: isCircle
    }"
    :aria-pressed="pressed"
    :type="type ? type : 'button'"
    :aria-label="label"
    :disabled="disabled"
    tabindex="0"
    role="button"
    @click="onClick"
    @blur="makeActionByBlur"

  >
    <slot/>
  </button>
</template>

<script	>
export default {
  name: "BaseButton",
  props: {
    isCircle: {
      type: Boolean,
      required: false,
      default: false
    },
    isDefaultPressed: {
      type: Boolean,
      required: false,
      default: false
    },
    pagination: {
      type: Boolean,
      required: false,
      default: false
    },
    modifier: {
      type: [String, Array],
      required: false,
      default: ""
    },
    unstyled: {
      type: Boolean,
      required: false,
      default: false
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    className: {
      type: String,
      required: false,
      default: null
    },
    action: {
      type: [Function, Boolean],
      required: false,
      default: false
    },
    type: {
      type: String,
      required: false,
      default: ""
    },
    label: {
      type: String,
      required: false,
      default: null
    },
    onBlur: {
      type: [Function, Boolean],
      required: false,
      default: false
    },
    microAction: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  data: () => ({
    pressed: false
  }),
  mounted() {
    this.$set(this, "pressed", this.isDefaultPressed);
  },
  methods: {
    onClick: function(event) {
      this.$set(this, "pressed", !this.pressed);
      if (this.action) {
        this.action(event);
      }
    },
    makeActionByBlur: function(event) {
      this.$set(this, "pressed", false);

      if (this.onBlur) {
        this.onBlur(event);
      }
    }
  }
};
</script>
<style lang="sass" scoped>
  @import '../assets/sass/conf/_colors.sass'
  @import '../assets/sass/conf/_sizes.sass'
  @import '../assets/sass/conf/_easing.sass'

  .unstyledButton
    border-width: 0
    background: none

  button
    cursor: pointer
    &:disabled
      opacity: .5
      cursor: default
      &:hover, &:active, &:focus
        transform: none

  .button
    position: relative
    padding: .8em 1.5em
    border-radius: 50px
    user-select: none
    z-index: 1
    transition: all 0.3s $standart
    will-change: transform
    transform-style: preserve-3d

    &_circle
      clip-path: circle()

    &_microAction
      background-color: $white
      box-shadow: 0 0 10px 1px rgba(0,0,0,.1)
      width: 20px
      height: 20px
      padding: 0

      &:not(:last-of-type)
        margin-right: .45rem

    &_pagination
      width: em(21, 11)
      height: em(21, 11)
      padding: 0
      font-size: em(11)

    &_white
      background-color: $white

    &_color

      &-gray
        color: #c4c4c4

    &_blue
      background: linear-gradient(0deg, $blue 0%, rgba($blue, .75) 100%)
      color: $white
    &_green
      $darkenGreen: darken($green, 15%)
      background: linear-gradient(0deg, $darkenGreen 0%, rgba($darkenGreen, .75) 100%)
      color: $white

    &:hover, &:focus
      transform: scale(1.02)

    &:active
      transform: scale(1.02) translateY(2px)

    &_centered
      margin-left: auto
      margin-right: auto


    &_strokeBlue
      background-color: $white
      border: 1px solid $blue
      color: $blue

    &_gray
      background-color: #cccccc
      color: $white

</style>
