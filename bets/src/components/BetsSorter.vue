<template>
  <!-- begin betsSorter -->
  <form
    method="get"
    class="betsSorter font-size_12"
    action="#">
    <!-- begin sorterSection -->
    <fieldset class="margin_vertical-base-tablet sorterSection sorterSection_styled margin_centered-phone">
      <legend class="visible-hidden">Сортировка по состоянию матчей</legend>
      <!-- begin  stateMatchesSorter -->
      <div class="stateMatchesSorter parent h-between height_fill wrap">

        <input
          id="live-bets-input"
          v-model="betsSorter.status"
          type="radio"
          name="betsSorterStatus"
          value="liveBets"
          
          @click="sortBets"
        >
        <label
          id="live-bets-label"
          for="live-bets-input"
          class="betsSorter__label round_25 relative text_centered"
        >
          Live ставки
        </label>
        <input
          id="all-input"
          v-model="betsSorter.status"
          type="radio"
          name="betsSorterStatus"
          value="all"
          @click="sortBets"
        >
        <label
          id="all-label"
          for="all-input"
          class="betsSorter__label round_25 relative text_centered"
        >
          Все матчи
        </label>
        <input
          id="results-input"
          v-model="betsSorter.status"
          type="radio"
          name="betsSorterStatus"
          value="results"
          @click="sortBets"
        >
        <label
          id="results-label"
          for="results-input"
          class="betsSorter__label round_25 relative text_centered"
        >
          Результаты
        </label>
      </div>
      <!-- end  stateMatchesSorter -->
    </fieldset>
    <!-- end sorterSection -->

    <!-- begin sorterSection -->
    <fieldset class="sorterSection sorterSection_styled margin_vertical-base-tablet margin_centered-phone" >
      <legend class="visible-hidden">Дата:</legend>
      <!-- begin timeRangeSorter -->
      <sorter-date
        since-date-name="betsSorterSinceDate"
        until-date-name="betsSorterUntilDate"
        @set-since-date="betsSorter.sinceDate = $event; sortBets()"
        @set-until-date="betsSorter.untilDate = $event; sortBets()"
      />
      <!-- end timeRageSorter -->
    </fieldset>
    <!-- end sorterSection -->

    <!-- begin sorterSection -->
    <fieldset class="relative sorterSection sorterSection_styled index_big margin_vertical-base-tablet margin_centered-phone">
      <!-- Для настройки типов сортировки можно преедать массив из объектов в атрибут sorter types. Настройки состояния находятся в src/store/bets.  -->
      <sorter-types
        :is-shown="$store.state.bets.isSorterTypesShown"
        :sorter-types="$store.state.bets.sorterTypes"
        :on-choose-sorter-type="switchSorterTypeAndSort"
        :switch-display-of-sorter-types="switchSorterTypesState"
        :current-sorter-type="$store.state.bets.currentSorterType"
        list-label="Список вариантов сортировки матчей"
      />
      <!-- end typeSorter -->
    </fieldset>
    <!-- end sorterSection -->

    <!-- begin sorterSection -->
    <fieldset class="margin_vertical-base-tablet sorterSection sorterSection_search margin_centered-phone">
      <legend
        id="searchLabel"
        class="visible-hidden"
      >
        Поиск
      </legend>
      <!-- begin searchSorter -->
      <div class="searchSorter relative height_fill padding-bottom_5 padding-top_5">

        <label
          for="searchSorter"
          class="searchSorter__label color_gray font-size_16 absolute position_base"
          aria-label="Поиск">
          <search-icon />
        </label>
        <input
          id="searchSorter"
          v-model="betsSorter.search"
          role="search"
          placeholder="Поиск"
          type="search"
          class="searchSorter__input width_fill height_fill"
          name="searchSorter"
          aria-describedby="searchLabel"
          @input="sortWithDelay"
        >
      </div>
      <!-- end searchSorter -->
    </fieldset>
    <!-- end sorterSection -->
  </form>
  <!-- end betsSorter -->
</template>

<script>
import { timer, timeout } from "@/constants/pureFunctions";
export default {
  name: "BetsSorter",
  data: () => ({
    betsSorter: {
      status: "",
      search: "",
      sinceDate: "",
      untilDate: "",
      type: ""
    }
  }),
  computed: {
    sortWithDelay() {
      return timer(this.makeSortBySearch);
    }
  },
  methods: {
    switchSorterTypesState() {
      this.$store.commit("bets/switchSorterTypesState");
    },

    switchSorterTypeAndSort(choosenType) {
      return () => {
        this.$store.dispatch("bets/choiceSorterTypeAndCloseList", choosenType);
        this.betsSorter.type = choosenType;
        this.sortBets();
      };
    },

    sortBets() {
      console.log(this.betsSorter);
    },

    makeSortBySearch() {
      this.sortBets();
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_easing.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'

.timeRageSorter
  margin-left: em(12, 12)
  
.dateInputContainer
  display: inline-block

  @include breakpoint($tablet)
    max-width: 40%

  @include breakpoint($desktop)
    max-width: em(50, 12)

  @include breakpoint($tablet-less)
    max-width: 40%


.searchSorter
  padding-left: em(21, 12)

  @include breakpoint($phone-less)
    padding-left: 0

  &__label
    top: em(8)
    left: em(21)
    font-size: em(16, 12)

    @include breakpoint($phone-less)
      left: em(5, 12)
      top: em(5, 12)

  &__input
    text-indent: 25px
    border-bottom: 1px solid $gray

    @include breakpoint($phone-less)
      text-indent: 35px

.betsSorter
    display: grid

    @include breakpoint($phone)
      grid-template-columns: repeat(2, minmax(320px, 50%))

    @include breakpoint($desktop)
      grid-template-columns: 331px 172px 125px 276px

    @include breakpoint($tablet)
      grid-auto-rows: 39px

    @include breakpoint($tablet-less)
        grid-auto-rows: auto
        display: flex
        flex-flow: row wrap
        justify-content: space-between
        padding-left:  1.5em
        padding-right:  1.5em

    grid-gap: 1rem em(32, 12)
    padding: em(48, 12) em(70, 12) em(20, 12)

    & > *
      object-fit: cover

    [type="radio"]
        white-space: nowrap
        overflow: hidden
        clip: rect(1px, 1px, 1px, 1px)
        width: 1px
        height: 1px
        position: absolute

    &__label
        margin-top: 3px
        height: 85%
        display: inline-block
        line-height: 2.5
        min-width: 33%
        transition: .3s $sharp




[type="radio"]:checked:not(:disabled) + label
    background: $buttonGradient
    color: $white

[type="radio"]:disabled label
    background: $gray $i

.datePickerChevron
  &_until
    right: em(20, 10)
  &_since

</style>
