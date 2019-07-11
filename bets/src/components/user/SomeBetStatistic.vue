<template>
  <!-- begin someBetStatistic -->
  <portal :to="betPortalId">
    <!-- begin someBetStatisticInfo -->
    <dl class="userSomeBetsSorterContainer someBetStatisticInfo background-color_white  parent row tableHead v-centered font-size_11 wrap someBetContainer_tablet padding-bottom_small-bet-tablet">
      <dt class="someBetStatisticInfo__title ">Коэффициент:</dt>
      <dd class="someBetStatisticInfo__info color_white parent centered">{{ ratio }}</dd>
      <dt class="someBetStatisticInfo__title ">Сумма:</dt>
      <dd class="someBetStatisticInfo__info color_white parent centered">$ {{ sum }}</dd>

      <dt
        v-if="acceptedSum"
        class="someBetStatisticInfo__title">Принятые ставки:</dt>
      <dd
        v-if="acceptedSum"
        class="someBetStatisticInfo__info color_white parent centered">$ {{ acceptedSum }}</dd>

      <dt class="someBetStatisticInfo__title">Возможный выигрыш:</dt>
      <dd class="someBetStatisticInfo__info color_white parent centered">$ {{ possibleWinSum }}</dd>
    </dl>
    <!-- end someBetStatisticInfo -->
  </portal>
</template>

<script>
export default {
  name: "SomeBetStatistic",
  props: {
    possibleWinSum: {
      type: [String, Number],
      required: true
    },
    sum: {
      type: [String, Number],
      required: true
    },
    acceptedSum: {
      type: [Number, Boolean, String],
      required: false,
      default: false
    },
    ratio: {
      type: [String, Number],
      required: true
    },
    betPortalId: {
      type: String,
      required: true,
      default: ""
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'

.someBetStatisticInfo
  min-height: 54px
  border-top: 0

  @include breakpoint($someBetTabletLess)
      display: grid
      grid-template-columns: repeat(4, 1fr)
      grid-column-gap: 40px
      grid-auto-rows: 40px
      padding-top: .25rem
      white-space: nowrap

  @include breakpoint($phone-less)
      grid-gap: 5px 15px
      grid-template-columns: 1fr
      grid-auto-rows: 22px
      padding-top: .75rem
      padding-bottom: 1.5rem

  &__title
    margin-right: .5rem
    font-weight: 600

  &__info
    border-radius: 5px
    background: $buttonGradient
    padding: .2rem .5rem .24rem

    @include breakpoint($phone-less)
        margin-bottom:  .5rem


    &:first-of-type
      @include breakpoint($someBetDesktop)
          margin-right: 2.7rem

    &:nth-of-type(2), &:nth-of-type(3)
      @include breakpoint($someBetDesktop)
          margin-right: 3rem

    &:nth-of-type(2)
      min-width: 58px

    &:nth-of-type(3)
      min-width: 62px







// .someBetStatisticRow
//   border-bottom: 0

  @include breakpoint($someBetTabletLess)
      padding-top: .75rem
      padding-bottom:  1.25rem
</style>
