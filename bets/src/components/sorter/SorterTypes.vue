<template>
  <div
    :class="{
      'typeSorter padding-bottom_5 parent row centered  width_fill height_fill relative': true,
      [`typeSorter__${modifier}`]: modifier,
      [className]: className,
      [`sorterSection sorterSection_styled`]: styled
    }"
    :style="{
      zIndex
  }">

    <p class="typeSorter__label croped_line pading-right_1 margin-top_zero">
      <span class="visible-hidden">Сортировка по пункту: </span>
      {{ currentSorterType || sorterTypes[0].name }}
    </p>

    <base-button
      :action="switchDisplayOfSorterTypes"
      :label="!isShown ? `Открыть выбор типа сортировки` : `Закрыть выбор типа сортировки`"
      class-name="circle typeSorter__button parent centered margin-left_auto"
      aria-haspopup="true"
      unstyled
    >
      <chevron-icon
        :position="isShown ? 'up' : 'down'"
      />
    </base-button>
    <transition
      appear
      name="fadeTranslateToBottom">
      <ul
        v-if="isShown"
        :aria-expanded="isShown"
        :aria-label="listLabel"
        class="absolute popupList"
      >
        <li
          v-for="(type, index) in sorterTypes"
          :key="index"
        >
          <!-- Можно сделать обычными ссылками -->
          <base-button
            :action="onChooseSorterType(type)"
            class-name="text_left"
            unstyled
          >
            {{ type.name }}
          </base-button>
        </li>
      </ul>
    </transition>
  </div>
  <!-- end typeSorter -->
</template>

<script>
export default {
  name: "SorterTypes",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    styled: {
      type: Boolean,
      required: false,
      default: false
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    isShown: {
      type: Boolean,
      required: true
    },
    listLabel: {
      type: String,
      required: true
    },
    sorterTypes: {
      type: Array,
      required: true
    },
    currentSorterType: {
      type: String,
      required: false,
      default: ""
    },
    onChooseSorterType: {
      type: Function,
      required: true
    },
    switchDisplayOfSorterTypes: {
      type: Function,
      required: true
    },
    zIndex: {
      type: Number,
      required: false,
      default: 1
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_easing.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'

.typeSorter
  max-width: em(120, 12)

  &__label
    max-width: 74%
    padding: 0 1em 0 2em
  &__button
    margin-top: em(3, 12)
    min-width: em(30, 12)
    background-color: $white $i
    margin-right: em(5, 12)
    min-height: em(30, 12)
</style>
