<template>
  <!-- begin transactionPagesPagination -->
  <div 
    :class="{
      'dataPagesPagination parent centered margin-top_increased-quarter margin-top_increased-tabletLess padding-bottom_increased-tabletLess': true,
      [className]: className,
      [`dataPagesPagination_${modifier}`]: modifier

  }">
    <!-- begin paginationButtons -->
    <div 
      :class="{
        'paginationButtons parent_inline row sorterSection_styled sorterSection sorterSection_zeroVerticalPadding': true,
        'margin-left_auto': rightPosition,
        'margin-right_auto': leftPosition,
        'paginationButtons_bloated': bloatedPagination
    }">
      
      <base-button
        v-for="(pageNumber, index) in quantityPages"
        :key="index"
        :action="onClickPaginationButton(pageNumber)"
        :modifier="currentPageNumber === pageNumber ? activeColor : 'color-gray'"
        :unstyled="currentPageNumber !== pageNumber"
        class-name="circle paginationButtons__button font-size_11"
        pagination

      >
        {{ pageNumber }}
      </base-button>
    </div>
    <!-- end paginationButtons -->
  </div>
  <!-- end transactionPagesPagination -->
</template>

<script>
export default {
  name: "DataPagination",
  props: {
    currentPageNumber: {
      type: Number,
      required: true
    },
    quantityPages: {
      type: Number,
      required: true
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
    // Функция передающаяся в функцию высшего порядка.
    // Имеет 1 позиционный аргумент - номер страницы.
    switchPage: {
      type: Function,
      required: true
    },
    dataName: {
      type: String,
      required: true
    },
    rightPosition: {
      type: Boolean,
      required: false,
      default: false
    },
    leftPosition: {
      type: Boolean,
      required: false,
      default: false
    },
    // Добавляет отсутпы кнопкам от краёв их конейнера.
    bloatedPagination: {
      type: Boolean,
      required: false,
      default: false
    },
    isUncolored: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  computed: {
    onClickPaginationButton() {
      return pageNumber => {
        return () => {
          this.switchPage(pageNumber, this.dataName);
        };
      };
    },
    activeColor() {
      return !this.isUncolored ? "blue" : "white";
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'
.dataPagesPagination 
  padding-bottom: 1.75em

.paginationButtons

  &_bloated
    padding: .25rem

  
  @include breakpoint($tablet-less)
      min-width: 0
      min-height: 0
</style>
