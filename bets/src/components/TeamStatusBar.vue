<template>
  <!-- begin teamStatusBar -->
  <div class="teamStatusBar overflow-y_auto max-height_126 relative background-color_white matchBetGrid h-centered">
    <!-- begin matchStatus__results -->
    <div class="matchStatusResults sticky parent row v-centered">
      <!-- begin matchStatusResultsContainer -->
      <div class="matchStatusResultsContainer width_fill parent row font-size_12 v-end">

        <lazy-image
          :src="teamName ? teamLogo : drawImage"
          class-name="parent v-centered matchStatusResults__image margin-right_small v-s-centered"
          relative
        />

        <div class="parent column">
          <span
            :class="{
              'status text_upper': true,
              'margin-bottom_base': !teamName
          }">{{ teamStatus }}
          </span>
          <h3 class="font-weight_semibold font-size_16">
            {{ teamName }}
          </h3>
        </div>
      </div>
      <!-- end matchStatusResultsContainer -->
    </div>
    <!-- end matchStatus__results -->
    <!-- begin offerBet -->
    <div class="grow offerBet betsSection parent row h-around relative">

      <p
        class="width_fill font-weight_semibold text_upper font-size_12 margin-top_zero parent centered alignParagraph display_none display_flex-tablet absolute position_base betsSection__label"
      >
        Предложить ставку
      </p>
      <!-- begin matchStatus__bet -->
      <div class="matchStatusBet matchStatusBet_1 parent column relative centered">
        <check-button
          :action="offerNewBet"
          :class-name="`absolute matchStatusBet__button${!bets.length ? ' matchStatusBet__button_topOffset' : ''}`"
          label="Принять ставку"/>
        <input
          id="offerBet"
          v-model="offerBet"
          aria-describedby="offerBetLabel"
          class="sample sample_input"
          type="number"
          name="offerBet"
          min="0"
        >
        <span
          v-for="(bet, index) in bets"
          :key="index"
          class="sample sample_disabled">

          {{ bet.offered.bet }}

        </span>
      </div>
      <!-- end matchStatus__bet -->

      <!-- begin matchStatus__ratio -->
      <div class="matchStatusRatio matchStatusRatio_1 parent column centered">
        <input
          id="offerRatio"
          v-model="offerRatio"
          aria-describedby="offerRatioLabel"
          class="sample sample_input"
          type="number"
          name="offerRatio"
          min="0"
        >
        <span
          v-for="(bet, index) in bets"
          :key="index"
          class="sample sample_disabled">
          {{ bet.offered.ratio | fixSum }}
        </span>
      </div>
      <!-- end matchStatus__ratio -->

      <!-- begin matchStatus__win -->
      <div class="matchStatusWin matchStatusWin_1 parent column centered">
        <input
          id="offerWin"
          v-model="offerWin"
          aria-describedby="offerRatioLabel"
          class="sample sample_disabled"
          type="number"
          name="offerRatio"
          readonly
        >
        <span
          v-for="(bet, index) in bets"
          :key="index"
          class="sample sample_disabled">
          {{ bet.offered.win }}
        </span>
      </div>
    <!-- end matchStatus__win -->
    </div>
    <!-- end offerBet -->

    <!-- begin acceptBet -->
    <div class="grow v-end acceptBet parent row h-around relative betsSection">
      <p
        class="width_fill font-weight_semibold text_upper font-size_12 margin-top_zero parent centered alignParagraph display_none display_flex-tablet absolute  betsSection__label betsSection__label_acceptMobile"
      >
        Принять ставку
      </p>
      <p
        :class="`acceptBet__notification ${betStatus === 'ничью' ? 'acceptBet__notification_topZero ' : ''}width_fill color_red font-size_10 margin-top_zero alignParagraph betsSection__label absolute `"
      >
        Принимая ставку, вы ставите на <strong>{{ betStatus }}</strong><span v-if="betStatus !== 'ничью'"> команды <strong>{{ oppositeTeamName }}</strong></span>.
      </p>
      <!-- begin matchStatus__bet -->
      <div class="matchStatusBet matchStatusBet_2 parent column centered">
        <input
          v-for="(bet, index) in bets"
          :key="index"
          :id="`acceptBet_${barId}_${index}`"
          v-model="bet.accepted.bet"
          aria-describedby="offerRatioLabel"
          class="sample sample_input"
          type="number"
          placeholder="350"
          min="0"
        >
      </div>
      <!-- end matchStatus__bet -->

      <!-- begin matchStatus__ratio -->
      <div class="matchStatusRatio matchStatusRatio_2 parent column centered">
        <span
          v-for="(bet, index) in bets"
          :key="index"
          class="sample sample_disabled">
          {{ bet.accepted.ratio | fixSum }}
        </span>
      </div>
      <!-- end matchStatus__ratio -->

      <!-- begin matchStatus__win -->
      <div class="matchStatusWin matchStatusWin_2 parent column centered">
        <span
          v-for="(bet, index) in bets"
          :key="index"
          :id="`acceptWin_${barId}_${index}`"
          class="sample sample_disabled">
          {{ bet.accepted.win }}
        </span>
      </div>
      <!-- end matchStatus__win -->
    <!-- end matchStatus__buttons -->
    </div>
    <!-- begin matchStatus__buttons -->
    <div class="matchStatusButtons parent column h-end">
      <check-button
        v-for="(bet, index) in bets"
        :key="index"
        :action="acceptUserBet(bet, index)"
        label="Принять ставку"
      />
    </div>
    <!-- end acceptBet -->
  </div>
  <!-- end teamStatusBar -->
</template>

<script>
import CheckButton from "@/components/CheckButton";
import { cup } from "@/assets/images/icons";
import { ID, timeout } from "@/constants/pureFunctions";
import { Observable, fromEvent } from "rxjs";
import { debounceTime, distinctUntilChanged } from "rxjs/operators";

export default {
  name: "TeamStatusBar",

  components: {
    CheckButton
  },

  props: {
    teamName: {
      type: String,
      required: true
    },
    oppositeTeamName: {
      type: String,
      required: true
    },
    teamLogo: {
      type: String,
      required: true
    },
    teamStatus: {
      type: String,
      required: true
    },
    bets: {
      type: Array,
      required: true
    }
  },

  data: () => ({
    drawImage: cup,
    offerBet: "",
    offerRatio: "",
    offerWin: ""
  }),

  computed: {
    barId() {
      return ID();
    },

    betStatus() {
      switch (this.teamStatus) {
        case "Победа":
          return "поражение";

        case "Ничья":
          return "ничью";

        default:
          return "победу";
      }
    }
  },

  watch: {
    offerBet(value) {
      if (value) {
        this.updateOfferedBetWinValue();
      }
    },

    offerRatio(value) {
      if (value) {
        this.updateOfferedBetWinValue();
      }
    }
  },

  mounted() {
    this.$nextTick(() => {
      timeout(() => {
        this.initDefaultWinValues();
      }, 500);
    });
  },

  methods: {
    initDefaultWinValues() {
      const barId = this.barId;

      this.bets.forEach((bet, index) => {
        const winFieldId = `acceptWin_${barId}_${index}`;
        this.bets[index].winField = document.getElementById(winFieldId);

        this.updateAcceptedBetWinValue(index);

        const accpetBetInputId = `acceptBet_${barId}_${index}`;
        const acceptBetInput = document.getElementById(accpetBetInputId);

        if (acceptBetInput) {
          const acceptBet = Observable::fromEvent(acceptBetInput, "input");

          const observer = {
            next: () => {
              this.updateAcceptedBetWinValue(index);
            }
          };

          this.bets[index].subscribe = acceptBet
            .pipe(debounceTime(500), distinctUntilChanged())
            .subscribe(observer);
        }
      });
    },

    offerNewBet() {
      const bet = this.offerBet;
      const ratio = this.offerRatio;

      if (!bet || !ratio) {
        const message = "Чтобы предложить ставку, заполните оба поля.";

        this.showBadMakingBet(message);

        return false;
      }

      const newUserBet = {
        bet: this.offerBet,
        ratio: this.offerRatio,
        win: this.offerWin
      };

      this.emitUserActionWithBet({
        action: "offer-new-bet",
        bet: newUserBet
      });
    },

    showBadMakingBet(message) {
      this.$store.dispatch("popups/openNotificationPopupWithMessage", {
        isError: true,
        isAuthorizationBlock: false,
        message
      });
    },

    updateOfferedBetWinValue() {
      const ratio = this.offerRatio;
      const bet = this.offerBet;

      if (ratio && bet) {
        this.offerWin = this.fixNumber(ratio * bet);
      }
    },

    acceptUserBet(bet, index) {
      return () => {
        if (!bet.accepted.bet) {
          const message =
            "Чтобы принять ставку, введите сумму, которую хотите поставить.";

          this.showBadMakingBet(message);

          return false;
        }

        const newUserBet = {
          accepted: bet.accepted,
          offered: bet.offered,
          bet: bet.bet
        };

        this.emitUserActionWithBet({
          action: "accept-user-bet",
          bet: newUserBet
        });
      };
    },

    emitUserActionWithBet({ action, bet }) {
      this.$store.commit("personalRoom/setNewUserBet", bet);
      this.$emit(action, bet);
    },

    fixNumber(number) {
      return number.toFixed(0);
    },

    updateAcceptedBetWinValue(index) {
      const acceptedBet = this.bets[index].accepted;
      const ratio = acceptedBet.ratio;
      const bet = acceptedBet.bet;
      const barId = this.barId;

      if (bet && ratio) {
        const winValue = this.fixNumber(bet * ratio);

        this.bets[index].accepted.win = winValue;

        this.updateWinField({
          index,
          value: winValue
        });
      } else {
        this.showZeroWinValue(index);
      }
    },

    updateWinField({ index, value }) {
      const winField = this.bets[index].winField;

      if (winField) {
        winField.textContent = value;
      }
    },

    showZeroWinValue(index) {
      this.updateWinField({
        index,
        value: 0
      });
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_easing.sass'
@import '../assets/sass/conf/_breakpoints.sass'

.acceptBet

  &__notification
    top: -11px
    left: 2px

    &_topZero
      top: 0

.betsSection
  @include breakpoint($tablet-less)
    padding-top: 1.5em
  &:nth-child(3)
    @include breakpoint($tablet-less)
      margin-top: 1.5em

  &__label
    @include breakpoint($phone-less)
      min-width: 122%

    &_acceptMobile
      left: 0
      top: -40px

.matchStatusButtons, .betsSection:nth-child(3)
  @include breakpoint($tablet-less)
    transform: translateY(2em)

.teamStatusBar
  transition: 200ms $sharp, width 300ms $sharp, 250ms transform $sharp
  will-change: transform, padding, box-shadow, widht, height
  z-index: 1
  width: calc(100% + 1.25em)
  margin-left: auto
  margin-right: auto

  @include breakpoint($phone)
    transform: scale(0.935)
  @include breakpoint($phone-less)
    transform: scale(0.935) translateX((-.7em))
    max-height: 225px

  &:last-of-type
    padding-bottom:  1.5em

  @include breakpoint($phone)
    padding-top: 1em

  &:hover
    @include breakpoint($phone)
      padding-bottom: 1em
    @include breakpoint($phone-less)
      padding-bottom: 1.5em

    -webkit-backface-visibility: hidden

    // @include breakpoint($phone)
    transform: scale(1) translateX((-1.25em / 2))

    // @include breakpoint($phone-less)
    //   transform: scale(1) translateX((-1em / 2))

    z-index: 2
    box-shadow: 0 2px 6px 0 rgba(0,0,0,.25)


.matchStatus

  &Results
    left: 0
    top: 0

    @include breakpoint($phone)
      max-height: 90px

    @include breakpoint($phone-less)
      max-height: 285px

    @include breakpoint($phone-less)
          position: static

    &__image
      max-width: em(30, 12)

  &Bet

    &_1
      grid-area: bet1
    &_2
      grid-area: bet2

      transform: translateY(-5px)
      @include breakpoint($tablet-less)
        transform: translateY(0)

    &__button
      left: em(-28)
      top: 0

      @include breakpoint($phone-less)
        left: 223px

      &_topOffset
        @include breakpoint($tablet)
          margin-top: .8em


  &Ratio

    &_1
      grid-area: ratio1
    &_2
      grid-area: ratio2

  &Win

    &_1
      grid-area: win1
    &_2
      grid-area: win2

  &Buttons
    min-width: em(42)
    @include breakpoint($phone)
      // transform: translateX(2.4em)
      min-width: em(86)
      padding-left: em(30)
    @include breakpoint($tablet)
      transform: translateX(-4px)
      padding-left: 0
      min-width: 0

.matchStatusResultsContainer
  height: fit-content
  margin-top: em(15, 12)
  margin-left: em(-6, 12)

  @include breakpoint($tablet-less)
    justify-content: center

.status
  color: #a4a7ae
  font-size: em(10, 12)

.sample
  min-width: em(61, 11.4)
  margin: .2em auto
  display: block
  max-width: em(61, 11.4)
  font-size: em(11.4)
  padding: .25em .25em
  text-align: center
  min-height: em(22, 11.4)
  box-sizing: border-box
  &_input
    border: 1px solid #ccc

  &_disabled
    background-color: #f8f8f8
    font-weight: 600


</style>
