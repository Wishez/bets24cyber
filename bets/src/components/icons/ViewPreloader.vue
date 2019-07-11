<template>
  <div 
    :class="{
      'width_fill height_fill parent preloaderContainer h-centered relative': true,
      'v-start': verticalPosition === 'top',
      'v-centered': verticalPosition === 'center',
      'v-end': verticalPosition === 'bottom',
      [className]: className
  }">
    <svg
      :class="{['preloader_sticky']: isSticky}"
      aria-live="assertive" 
      role="alert"
      aria-label="Загрузка страницы"
      height="1em" 
      width="1em" 
      viewbox="0 0 44 44" 
    >
      <use xlink:href="#pagePreloader"/>
    </svg>
  </div>
</template>

<script>
export default {
  name: "ViewPreloader",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },

    verticalPosition: {
      type: String,
      required: false,
      default: "center",
      validation: value => {
        return ["center", "top", "bottom"].indexOf(value) !== -1;
      }
    },

    isSticky: {
      type: Boolean,
      required: false,
      default: true
    }
  }
};
</script>

<style lang="sass" scoped>
.preloaderContainer
  font-size: 44px
.preloader
  &_sticky
    position: sticky
    top: 25%

</style>
