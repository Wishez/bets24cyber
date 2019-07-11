<template>
  <section 
    :class="{
      'section parent row wrap h-between padding-top_base': true, 
      'container': isContainer,
      ['section_' + modifier]: modifier, 
      [className]: className
  }">
    <h2 class="relative text_upper section__title endlessBottomBorder endlessBottomBorder_gray padding-bottom_small">{{ title }}</h2>
    
    <slot/>
  </section>
</template>

<script>
export default {
  name: "BaseSection",
  props: {
    isContainer: {
      type: Boolean,
      required: false,
      default: true
    },
    className: {
      type: String,
      required: false,
      default: ""
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    title: {
      type: String,
      required: true
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_sizes.sass'

.section__title
  box-shadow: inset 0 -5px 0 #2961e6

  
</style>
