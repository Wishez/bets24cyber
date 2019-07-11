<template>
  <base-button 
    :class="{
      'iconButton parent centered circle': true, 
      ['iconButton_' + modifier]: modifier, 
      [className]: className
    }"
    :action="action"
    unstyled>
    <icon 
      v-if="isAwesomeIcon"
      :name="icon" />
    <slot/>
  </base-button>
</template>

<script>
export default {
  name: "ButtonIcon",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    // This boolean property for checking to use vue-awesome icons or just take custom svg-icon.
    isAwesomeIcon: {
      type: Boolean,
      required: false,
      default: true
    },
    icon: {
      type: String,
      required: false,
      default: "star"
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    action: {
      type: Function,
      required: true
    }
  }
};
</script>

<style lang="sass" scoped>
	@import '../assets/sass/conf/_colors.sass'
	@import '../assets/sass/conf/_sizes.sass'

	.iconButton
		// padding: 0.7em
		min-width: em(32)
		min-height: em(32)
		&_blue
			background-color: $blue $i
		&_gray
			background-color: rgb(204, 204, 204) $i
</style>
