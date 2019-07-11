<template>

  <div class="width_fill font-size_12 borderBox">
    <!-- begin tableHeadRow -->
    <div class="tableHead parent row wrap">
      <!-- begin league -->
      <div class="league wrap blockOffsets grow v-centered height_fill parent row">

        <!-- begin leagueLogo -->
        <div
          class="tableHeadCeil tableHeadCeil_leagueLogo"/>
        <!-- end leagueLogo -->

        <!-- begin leagueName -->
        <div
          class="tableHeadCeil tableHeadCeil_leagueName margin_vertical-small-phone">
          Турнир
        </div>
        <!-- end leagueName -->

        <!-- begin date -->
        <div class="tableHeadCeil tableHeadCeil_date margin_vertical-small-phone">
          Дата
        </div>
        <!-- end date -->

        <!-- begin time -->
        <div class="tableHeadCeil tableHeadCeil_time margin_vertical-small-phone">
          Время
        </div>
        <!-- end time -->
      </div>
      <!-- end league -->

      <!-- begin teams -->
      <div class="teams wrap blockOffsets grow parent row height_fill v-centered margin_vertical-small-phone ">

        <!-- begin team -->
        <div class="width_fill-phone team team_right parent row v-centered height_fill grow margin_vertical-small-phone h-around_phone row_reverse-phone margin-top_small-tabletLess">

          <!-- begin teamName -->
          <div class="tableHeadCeil tableHeadCeil_teamName text_right text_centered-phone">
            Команда
          </div>
          <!-- end teamName -->

          <!-- begin teamLogo -->
          <div class="tableHeadCeil tableHeadCeil_teamLogo"/>
          <!-- end teamLogo -->

          <!-- begin teamCountry -->
          <div class="tableHeadCeil tableHeadCeil_teamCountry">
            Страна
          </div>
          <!-- end teamCountry -->

          <!-- begin teamRation -->
          <div class="tableHeadCeil tableHeadCeil_teamRatio text_centered">
            К1
          </div>
          <!-- end teamRation -->

        </div>
        <!-- end team -->

        <!-- begin versus -->
        <div class="tableHeadCeil tableHeadCeil_versus"/>
        <!-- end versus -->

        <!-- begin team -->
        <div class="width_fill-phone team team_right parent row v-centered height_fill grow margin_vertical-small-phone margin_vertical-small-phone h-around_phone margin-top_small-tabletLess">

          <!-- begin teamRatio -->
          <div class="tableHeadCeil tableHeadCeil_teamRatio text_centered">
            К2
          </div>
          <!-- end teamRatio -->

          <!-- begin teamCountry -->
          <div class="tableHeadCeil tableHeadCeil_teamCountry">
            Страна
          </div>
          <!-- end teamCountry -->

          <!-- begin teamLogo -->
          <div
            class="tableHeadCeil tableHeadCeil_teamLogo"/>
          <!-- end teamLogo -->

          <!-- begin teamName -->
          <div class="tableHeadCeil tableHeadCeil_teamName text_left font-size_11 text_centered-phone">
            Комнада
          </div>
          <!-- end teamName -->
        </div>
        <!-- end team -->

        <!-- begin watchMatch -->
        <div class="margin_vertical-small-phone tableHeadCeil  tableHeadCeil_watchMatch parent centered margin-top_small-tabletLess">
          Сомотреть матч
        </div>
        <!-- end watchMatch -->

      </div>
      <!-- end teams -->

    </div>
    <!-- end tableHeadRow -->

    <!-- begin tableContent -->

    <div
      v-if="isBetsShown"
      class="tableContent display_grid">
      <slot/>
    </div>
    <icon-preloader
      v-else
      class-name="min-height_offset"
    />
    <!-- end tableContent -->
  </div>

</template>

<script>
export default {
  name: "BetsTable",
  props: {
    isBetsShown: {
      type: [Boolean, Number],
      required: false,
      default: true
    },
    onChangeSorterSettings: {
      type: Function,
      required: true
    }
  },
  created() {
    this.$emit("load-matches");
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'

.tableContent
  @include breakpoint($betsDesktop)
    grid-auto-rows: 61px


.tableHeadCeil
  font-size: 10px
  font-weight: 200
  flex: 1 0 auto

  // Контейнер
  &_leagueLogo
    min-width: em(120, 10)
    max-width: em(160, 10)
    // padding-right: 1.3em
    @include breakpoint($betsTabletLess)
      display: none

  &_leagueName
    // min-width: em(236, 10)
    max-width: em(236, 10)
    @include breakpoint($desktop)
      min-width: em(236, 12) //206
    @include breakpoint($desktop-less)
      min-width: em(128, 12)
    @include breakpoint($betsTabletLess)
      min-width: 70%
      text-align: center

    @include breakpoint($phone-less)
      min-width: 100%
      text-align: center

  &_date
    @include breakpoint($phone)
      max-width: 15%
      text-indent: 34px
    @include breakpoint($betsDesktop)
      max-width: em(64, 10)
      text-indent: 12px
    @include breakpoint($phone-less)
      min-width: 50%
      text-align: right
      padding-right: 1em
  &_time
    @include breakpoint($phone)
      max-width: 15%
      text-indent: 34px
    @include breakpoint($betsDesktop)
      max-width: em(43, 10)
      text-indent: 0

    @include breakpoint($phone-less)
      min-width: 50%
      padding-left: 1em

  // Контейнер
  &_teamLogo
    min-width: em(40, 10)
    max-width: em(60, 10)
  &_teamName
    min-width: em(90, 10)
    max-width: em(92, 10)
  // Контейнер
  &_teamRatio
    max-width: em(47, 10)

  &_versus
    @include breakpoint($phone)
      max-width: em(30, 10)
      min-width: em(30, 10)

  &_teamCountry
    max-width: em(34, 10)
  &_watchMatch
    @include breakpoint(em(1009) em($containerWidth))
      display: none

</style>
