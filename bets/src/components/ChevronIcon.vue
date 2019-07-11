<template>
  <svg 
    :class="{
      chevron: true,
      [`chevron_${position}`]: position,
      [classes]: classes
    }"
    role="img" 
    aria-hidden="true" 
    focusable="false" 
    tabindex="-1"
    width="1em" 
    height="1em" 
    viewBox="0 0 10 10"
  >
    <path 
      fill="currentColor" 
      d="M6.7,8.8L3,5l3.6-3.8c0.3-0.3,0.2-0.7,0-1c-0.2-0.3-0.7
    -0.2-1,0l-4,4.3c-0.2,0.3-0.2,0.7,0,0.9l4.1,4.3c0.3,0.3,0.7,0.3,1,0
    C7,9.5,7,9.1,6.7,8.8z"/>
  </svg>
</template>

<script>
export default {
  name: "ChevronIcon",
  props: {
    classes: {
      type: String,
      required: false,
      default: "down"
    },
    position: {
      type: String,
      required: false,
      default: "down",
      validator: function(value) {
        const directions = ["down", "up", "left", "right"];
        return directions.indexOf(value) !== -1;
      }
    }
  }
};
</script>

<style lang="sass" scoped>
	@import '../assets/sass/conf/_easing.sass'
	.chevron
		transition: transform .2s $decelleration
		will-change: transform
		&_down
			transform: rotate(-90deg)
		&_up
			transform: rotate(90deg)
		&_left
		&_right
			transform: rotate(-180deg)
</style>
