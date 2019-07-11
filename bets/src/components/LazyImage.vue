<template>
  <div 
    :class="{
      'imageContainer': true,
      'fitBlock': fitBlock,
      ['imageContainer_' + modifier]: modifier,
      [className]: className,
      'imageContainer_relative': relative,
      'imageContainer_absolute': absolute,
      'imageContainer_fillHeight': isImageFillContainer,
  }">
  
    <img 
      v-lazy="src"
      :alt="alt"
      :class="{
        'imageContainer__image': true,
        [imageClasses]: imageClasses,
        'fitBlock': isImageFillContainer,
        'imageContainer__image_circle': circle,
      }"
    >
    <slot />
  </div>
</template>

<script>
export default {
  name: "LazyImage",
  props: {
    circle: {
      type: Boolean,
      required: false,
      default: false
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    fitBlock: {
      type: Boolean,
      required: false,
      default: true
    },
    relative: {
      type: Boolean,
      required: false,
      default: false
    },
    absolute: {
      type: Boolean,
      required: false,
      default: false
    },
    isImageFillContainer: {
      type: Boolean,
      required: false,
      default: false
    },

    alt: {
      type: String,
      required: false,
      default: ""
    },
    src: {
      type: String,
      required: true
    },
    className: {
      type: String,
      required: false,
      default: ""
    },
    imageClasses: {
      type: String,
      required: false,
      default: ""
    }
  }
};
</script>

<style lang="sass" scoped>
.imageContainer
  // height: auto
  &_fillHeight
    height: 100%
  
  &_relative
    .progressive-image-main, .imageContainer__image, .imageContainer__image
      position: relative
  &_absolute
    position: absolute
    z-index: -1

.imageContainer__image
  &_circle
    clip-path: circle()
</style>
