<template>
  <modal-container
    :on-close-modal="closePersonalRoom"
    :on-open="() => {}"
    modifier="big"
    class-name="relative parent row personalRoomLitter"
    is-big-close-button
  >
    <slot />
  </modal-container>
</template>

<script>
// Constants
import { timeout } from "@/constants/pureFunctions";

import popupsNames from "@/constants/popups";

export default {
  name: "PersonalRoom",
  props: {
    onChangeCreatedBetsSorterSettings: {
      type: Function,
      required: true
    },
    onChangeTransactionsSorterSettings: {
      type: Function,
      required: true
    },
    onChangeAcceptedBetsSorterSettings: {
      type: Function,
      required: true
    },
    onChangeContactCenterSorterSettings: {
      type: Function,
      required: true
    }
  },
  data: () => ({
    isLoadedData: false
  }),
  computed: {
    paginations() {
      return this.$store.state.pagination;
    },
    user() {
      return this.$store.state.personalRoom.user;
    }
  },
  watch: {
    isLoadedData(newValue, oldValue) {
      if (newValue) {
        this.$nextTick(() => {
          this.initPerosnalRoom();
        });
      }
    }
  },
  beforeUpdate() {
    this.$nextTick(() => {
      // Обновление количества страниц.

      const choosenThread = this.user.choosenThread;
      const choosenThreadPagination = this.paginations.choosenThread;

      if (choosenThread.isOpened && choosenThread.data) {
        choosenThreadPagination.quantityPages = Math.ceil(
          choosenThread.data.dialog.length / choosenThreadPagination.dataPerPage
        );
      }
    });
  },
  destroyed() {
    this.$nextTick(() => {
      ["transactions", "acceptedBets", "createdBets", "contactCenter"].forEach(
        moduleName => {
          // First.
          this.$store.commit(`personalRoom/cleanUserData`, moduleName);
        }
      );
    });
  },
  beforeMount() {
    this.$emit("open-personal-room", this);
  },

  methods: {
    initPerosnalRoom() {
      const userData = this.user;
      const paginations = this.paginations;
      const threadData = userData.choosenThread.data;

      // При загрузки комнаты, компонуется пагинация для каждого модуля.
      ["transactions", "acceptedBets", "createdBets", "contactCenter"].forEach(
        moduleName => {
          const modulePagination = paginations[moduleName];

          timeout(() => {
            modulePagination.quantityPages = Math.ceil(
              userData[moduleName].length / modulePagination.dataPerPage
            );
          }, 250);
        }
      );

      // If there is data in thread, then we can to update a thread in the view.
      if (threadData) {
        this.$nextTick(() => {
          const choosenThreadPagination = paginations.choosenThread;

          choosenThreadPagination.quantityPages = Math.ceil(
            threadData.dialog.length / choosenThreadPagination.dataPerPage
          );
        });
      }
    },
    closePersonalRoom() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: false,
        popupName: popupsNames.personalRoom
      });
    }
  }
};
</script>

<style lang="sass">
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'


.personalRoom
  position: relative
  height: 100%

.font-size_11-thread
  font-size: em(13, 12)

.font-size_10-thread
  font-size: em(10, 12)

.messageLitter
    position: relative
    z-index: 0
    box-shadow: 1px 1px 2px 1px rgba(0, 0, 0, 0.1)
    border-radius: 5px

    &_bloated
      padding: 1rem 1rem 1.25rem

      &-small
        padding: 0.7rem  (23rem / 16) 0.7rem 1rem



.userThreadHistory
  padding-left: 1.35rem
  padding-right: 1.35rem
  overflow-y: auto
  max-height: em(700, 12)
  padding-bottom: 1em


.threadUserMessage

  &__input
    width: 100% $i
    max-height: 71px
.threadUserMessageContainer
  padding-top: 3.5rem
  // padding-right: .6rem

.threadUserMessageFiles

  &__button
    padding-top: .2rem $i
    padding-bottom: .25rem $i
    min-width: 83px

  &__filesButton
    margin-right: em(12, 10)
    padding: 0 .5rem .1rem
    border-radius: 30px
    border: 1px solid
    min-width: em(110, 10)

  &__list
    max-width: 385px


.newThreadPopup
  max-height: em(298)

.userThread



  @include breakpoint($phone)
    grid-auto-rows: auto 1fr 177px auto

  @include breakpoint($someBetDesktop)
    grid-auto-rows: 82px 1fr 177px 78px

  @include breakpoint($phone-less)
    grid-auto-rows: auto 1fr 220px auto



.contactCenterData


  @include breakpoint($someBetDesktop)
    grid-auto-rows: 82px


.grid_three

  &-phoneUp

    @include breakpoint($phone)
      grid-template-columns: repeat(3, 1fr)
      grid-gap: 30px

.userContactCenterSorter


  @include breakpoint($tablet)
    grid-template-columns: 165px 139px 101px

  @include breakpoint($phone-less)
    grid-gap: 1rem
    justify-content: center

  &__button
    font-size: em(12, 10)

.personalRoomContactCenter

  @include breakpoint($someBetDesktop)
    grid-template-rows: 79px 1fr

  @include breakpoint($someBetTabletLess)
    grid-template-rows: auto 1fr

  @include breakpoint($phone-less)

.userMessagesGrid
  grid-column-gap: 30px

  @include breakpoint($phone)
    grid-template-columns: 1fr 1fr 75px

  @include breakpoint($betsTabletLess)
    justify-content: center
    grid-row-gap: .5rem




  @include breakpoint($someBetDesktop)
    grid-template-columns: 62px 232px 134px 118px 80px
    grid-template-areas: "id title date status actions"

  @include breakpoint($someBetTabletLess)
    grid-template-areas: "id date actions" "title status actions"
    justify-content: space-between

  @include breakpoint($tablet-less)
    grid-gap: .5rem
    padding-top: .75rem $i
    padding-bottom: 1.25rem $i

  @include breakpoint($phone-less)
    text-align: center
    grid-template-areas:  "id date" "title title"  "status status" "actions actions"



  &Id, &LastAnswerDate
    text-align: center

  &Actions
    grid-area: actions

    @include breakpoint($phone-less)
        justify-content: center

  &Id
    grid-area: id

    @include breakpoint($tablet-less)
      transform: translateX(2.5rem)

    @include breakpoint($phone-less)
      transform: none

  &Title
    grid-area: title

  &Status
    grid-area: status

  &LastAnswerDate
    grid-area: date

.someBetsData
  background-color: #f9f9f9

  @include breakpoint($phone)
      grid-row-gap: 14px

  @include breakpoint($someBetDesktop)
      grid-row-gap: 0



.userSomeBets

  @include breakpoint($phone)
      grid-template-rows: 100px 1fr

  @include breakpoint($tablet)
      grid-template-rows: 80px 1fr

  @include breakpoint($tablet-less)
      align-items: center
      grid-gap: 5px

.personalRoomSection

  &_phone

    @include breakpoint($phone-less)
      // grid-auto-rows:  1fr
      align-items: center

.userSomeBetsSorterContainer
  @include breakpoint($someBetDesktop)
      padding: 0 em(29) 0 1rem

  &__title
    @include breakpoint($someBetTabletLess)
      margin-right:  3rem

.padding

  &-bottom

    &_small-bet-tablet

        @include breakpoint($someBetTabletLess)
            padding-bottom: .5rem

  &_vertical-bet-tablet

      @include breakpoint($someBetTabletLess)
          padding-top: .75rem
          padding-bottom: 1rem

.personalRoomSorter
  font-size: em(9.6)

  @include breakpoint($tablet-less)
    justify-content: flex-end

  @include breakpoint($phone-less)
    grid-gap: 1rem
    justify-content: center

  & > *

      @include breakpoint($someBetTabletLess)
          min-width: 100% $i

.userSomeBetsSorter

  @include breakpoint($someBetDesktop)
      grid-template-columns: 139px repeat(2, 101px)
      grid-gap: 25px

  @include breakpoint($someBetTabletLess)
      flex-grow: 1
      grid-gap: 15px





.someBetsGrid
  grid-column-gap: 11px

  @include breakpoint($someBetDesktop)
      grid-template-columns: 46px 38px 74px 211px 135px 100px  104px
      grid-template-areas: "id game date match  bet status actions"

  @include breakpoint($someBetTabletLess)
      grid-template-columns: repeat(4, 1fr)
      grid-template-areas: "id date status actions" "game bet match match"

  @include breakpoint($phone-less)
      grid-template-areas: "status status status status" "match match match match" "id game date date" "bet bet bet actions"

  &_column

    @include breakpoint($someBetTabletLess)
        grid-row-gap: .5rem

    @include breakpoint($phone-less)
      padding-top: .5rem $i
      padding-bottom: 1rem $i


  &_row
    @include breakpoint($someBetTabletLess)
      grid-row-gap: 11px
      grid-auto-rows: 35px




  &Id
    grid-area: id

  &Match
    grid-area: match

  &Bet
    grid-area: bet
    span
        @include breakpoint($someBetTabletLess)
            text-align: left


  &GameLogo
    grid-area: game
    @include breakpoint($someBetTabletLess)
        margin: auto

  &Status
    grid-area: status

  &Date
    grid-area: date
    @include breakpoint($someBetTabletLess)
        // text-indent: 0
        text-align: center


  &Actions
    grid-area: actions

    @include breakpoint($someBetTabletLess)
        justify-content: center
        padding-left:  1.25rem

    @include breakpoint($phone-less)
        padding-left: 0
        white-space: nowrap

  &Id, &Match, &Bet, &Status
    text-align: center

  &Bet
    @include breakpoint($phone-less)
        text-align: left
        padding-left: 0



#personalRoom

  .userTransactions

    &Head

      @include breakpoint($tablet-less)
        grid-row-gap: .25em

    &Body

      @include breakpoint($tablet)
        grid-auto-rows: 61px

      @include breakpoint($tablet-less)
        grid-auto-rows: 105px

  .personalRoomBalance
    background-color: #f4f3f3
    grid-template-areas: "actions" "payments" "transactions"


    @include breakpoint($tablet)
      grid-template-rows: 92px 183.33333333333333333333px 80px 1fr;

    @include breakpoint($someBetDesktop)
      grid-template-rows: 92px 183.33333333333333333333px 60px 1fr

    @include breakpoint($tablet-less)
      // grid-template-rows: minmax(92px, 140px) repeat(2, 1fr)

    @include breakpoint($small-phone-less)
      // grid-template-rows: repeat(3, 1fr)

  .personalRoomUserPanel
    height: auto
    min-width: em(247)
    margin-bottom: 0
    will-change: transform
    transition: transform 250ms $acceleration

    &_hidden
      transition: transform 250ms $decelleration
      transform: translateY(-101%)

    @include breakpoint($betsTabletLess)
      position: static
      max-height: em(195)
      min-width: 100%
      display: grid
      grid-template-columns: 1fr
      grid-auto-rows: 85px 70px
      grid-gap: 0 10px
      height: auto
      min-height: 0
      padding: 2.5em 0 0

    @include breakpoint($phone-less)
      grid-template-columns: 100%

  .personalRoomNavigationList
    @include breakpoint($betsDesktop)
        padding-bottom: 2.75em

    @include breakpoint($betsTabletLess)

      display: grid
      grid-template-columns: repeat(auto-fit, minmax(125px, 1fr))
    @include breakpoint($phone-less)
      grid-template-columns: repeat(auto-fit, minmax(70px, 1fr))

  .userInfo

    @include breakpoint($betsDesktop)
      padding-top: em(55)
      margin-bottom: 4.2rem

    @include breakpoint($betsTabletLess)
      flex-direction: row
      justify-content: space-around
      min-width: em(475)

    @include breakpoint($phone-less)
      display: grid
      min-width: 0
      grid-template-columns: 100px 1fr
      grid-template-rows: 50px 20px
      grid-template-areas: "userAvatar userName" "userAvatar userBalance"

    &__balance
      grid-area: userBalance

    &__title
      grid-area: userName

    &Avatar
      grid-area: userAvatar
      width: em(83)
      height: em(83)
      border: 2px solid $white
      box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3)
      &:hover
        .userInfoAvatar__button
          opacity: 1

      &__button
        background: rgba($darkGray, .3)
        opacity: 0
        will-change: opacity
        transition: opacity 250ms $sharp



  .userInfoMessages
    font-size: em(7.5)

    @include breakpoint($betsDesktop)
      top: em(36, 7.5)
      right: em(45, 7.5)

    @include breakpoint($betsTabletLess)
      bottom: 100%
      left: 17em
    @include breakpoint($phone-less)
      left: 12.5em

    &__icon
      fill: rgb(119, 124, 137)
      &_blue
        // fill: $darkBlue
        & + .userInfoMessages__quantity
          // color: $white
          // background-color: $

    &__quantity
      top: em(-8, 7.5)
      right: em(-10, 7.5)

    &__quantity, &__icon
      width: em(20, 7.5)
      height: em(20, 7.5)

  .sorterContainer

    @include breakpoint($tablet)
      padding: 1rem 2.2rem .7rem

    &__title
      margin-right: 5rem

  .transactionsSorter
    font-size: em(9.6)


    @include breakpoint($phone)
      grid-template-columns: repeat(2, minmax(101px, 1fr))
      grid-template-areas: "transactionsTime transactionsBets" "transactionsType transactionsStatus"

    @include breakpoint($tablet)
      grid-template-columns: 139px repeat(auto-fit, minmax(101px, 1fr))
      grid-template-areas: "transactionsTime transactionsBets transactionsType transactionsStatus"
      grid-column-gap: 25px

    @include breakpoint($tablet-less)
      grid-auto-rows: 40px
      align-items: center
      grid-gap: .5em 1.5em

    @include breakpoint($phone-less)
      grid-template-columns: 100%

    & > *
      max-height: 33px
      @include breakpoint($tablet-less)
        min-width: 0
        max-width: none

  .transactionsTimeRangeSorter
    grid-area: transactionsTime

  .transactionsBetsSorter
    grid-area: transactionsBets

  .transactionsStatusSorter
    grid-area: transactionsStatus

  .transactionsTypeSorter
    grid-area: transactionsType

  .personalRoomSectionLink

    & *
      transition: background .2s $sharp, color .1s $sharp, border-color .2s $sharp

    &:hover

      .personalRoomSectionLink__label
        color: $blue $i

      .personalRoomSectionLink__icon
        background-color: $blue $i

    &__label

      @include breakpoint($betsTabletLess)
        margin-top: .3em

    &__button
      border: 3px solid transparent
      min-height: em(40, 14)

      &_active
        & .personalRoomSectionLink__label
          color: $blue


      @include breakpoint($betsDesktop)
          padding-bottom: 0

      @include breakpoint($betsTabletLess)

      @include breakpoint($betsTabletLess)
        padding-bottom: .75em
        flex-direction: column

      @include breakpoint($phone-less)
        font-size: em(12)
        padding-left: .25rem
        padding-right: .25rem

      &_active


        @include breakpoint($betsDesktop)
          border-left-color: $blue
          border-bottom-color: transparent

        @include breakpoint($betsTabletLess)
          border-bottom-color: $blue


    &__icon
      margin: 0 1.25rem 0 1.6rem

    &:first-of-type

      .personalRoomSectionLink__button

        @include breakpoint($small-phone-less)
          padding-bottom: 1.3rem


  .personalRoomNavigation
    top: 20%

  .userPaymentActionButtons
    grid-area: actions

    @include breakpoint($tablet)
      padding-left: 3em
    @include breakpoint($tablet-less)

      padding-top: 1.5rem
      padding-bottom: 2rem

      @supports (display: grid)
        display: grid
        grid-gap: 1rem
        grid-template-columns: repeat(auto-fit, minmax(129px, 1fr))

    &__button
      min-width: em(129)

      @include breakpoint($small-phone-less)
          margin-left: .75rem



      @include breakpoint($small-phone-less)
        margin-left: 0

      &:not(:last-of-type)
        @include breakpoint($tablet)
          margin-right: em(20)



      @include breakpoint($small-phone-less)
        margin-top: .5rem

        @supports (display: grid)
          margin-top: 0

  .userPaymentWays
    grid-area: payments

    @include breakpoint($tablet)
      padding-left: 2em
      padding-bottom: 2.1em

  @for $index from 1 through 6
    .paymentWay:nth-of-type(#{$index})
      z-index: (7 - $index)


  .paymentWay
    min-width: em(92, 12.63)
    font-size: em(12.63)
    will-change: opacity, filter,  background-color, box-shadow, transform, z-index

    transition-duration: 233ms
    transition-timing-function: $standart
    transition-property: opacity, filter,  background-color, box-shadow, transform, z-index
    margin-top: 0
    min-height: 113px
    min-width: 113px

    // &:not(:first-of-type, :last-of-type)
    //   // padding-right: 1.5em

    &:first-of-type
      // padding-right: 1em
      .paymentWay__icon
        margin-bottom: 1em

    &_disabled
      filter: grayscale(1)
      opacity: .2

    &_active
      background-color: $white
      box-shadow: 0 0 10px -2px rgba(0,0,0,.2)

      &:not(:last-of-type):after
          width: 5px
          height: 100%
          top: 0
          right: -4px
          background-color: $white
          will-change: opacity
          transition: 100ms opacity $standart

      &:hover, &:focus
        transform: scale(1.05)
        z-index: 10
        &:after
          opacity: 0

  .userTransactions
    grid-area: transactions

.text

  &_centered-bet-tablet

      @include breakpoint($someBetTabletLess)
          text-align: center

  &-indent_zero-bet-tablet

      @include breakpoint($someBetTabletLess)
        text-indent: 0


.someBetContainer_tablet

  @include breakpoint($someBetTabletLess)
      padding: 1rem 1.25rem 1.75rem 2rem


</style>
