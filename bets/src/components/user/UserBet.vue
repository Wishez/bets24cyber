<template>
  <div>
    <!-- begin someBetsTableRow  -->
    <div
      :class="`someBetsTableRow someBetsGrid someBetsGrid_row someBetsGrid_rows display_grid centered height_fill bordered-bottom bordered-bottom_gray someBetContainer_tablet padding-bottom_base-phone-rem ${className}`"
      role="row"
      @click="changeStatisticState"
    >
      <!-- begin someBetsGridId -->
      <span
        role="cell"
        class="someBetsGridId font-weight_semibold">
        {{ betId }}
      </span>
      <!-- end someBetsGridId -->

      <!-- begin someBetsGridId -->
      <lazy-image
        :src="gameImage"
        :fit-block="false"
        role="cell"
        class="someBetsGridGameLogo margin-left_small" />

      <!-- end someBetsGridId -->

      <!-- begin someBetsGridId -->
      <time
        :datetime="matchDate | formatDate('L')"
        class="someBetsGridDate color_paleBlue"
        role="cell"
      >
        {{ matchDate | formatDate("DD MMMM YYYY, HH:mm") }}
      </time>
      <!-- end someBetsGridId -->


      <!-- begin someBetsGridMatch -->
      <div
        class="someBetsGridMatch  display_grid centered someBetsGridMatch_row"
        role="cell"
      >
        <!-- begin teamLeft -->
        <div class="someBetsTeam someBetsTeam_left parent column centered grow padding-left_half-quarter">
          <h3 class="someBetsTeam__title font-weight_semibold font-size_12-table someBetsTeam__title_left croped_line">
            {{ leftTeamName }}
          </h3>

          <lazy-image
            :fit-block="false"
            :src="leftTeamLogo"
            class-name="order_first someBetsTeam__image parent_inline v-centered max-width_20"
          />
        </div>
        <!-- end teamLeft -->

        <!-- begin someBetsTeamVersus -->
        <span class="someBetsMatchVersus color_white font-size_22 font-weight_extrabold parent centered">VS</span>
        <!-- end someBetsTeamVersus -->

        <!-- begin someBetsTeamRight -->
        <div class="someBetsTeam someBetsTeam_right parent column centered grow">
          <h3 class="someBetsTeam__title font-weight_semibold font-size_12-table someBetsTeam__title_right croped_line">
            {{ rightTeamName }}
          </h3>

          <lazy-image
            :src="rightTeamLogo"
            :fit-block="false"
            class-name="order_first someBetsTeam__image max-width_20 parent_inline v-centered"
          />
        </div>
      <!-- end someBetsTeamRight -->
      </div>
      <!-- end someBetsGridMatch -->

      <!-- begin someBetsGridBet -->
      <div
        :title="noteAboutEndMatch"
        class="someBetsGridBet padding-left_large parent row wrap"
        role="cell"
      >
        <h3 class="someBetsGridBet__title grow parent v-centered font-weight_semibold font-size_base croped_line">
          {{ winTeamName }}
        </h3>
        <lazy-image
          :src="winTeamLogo"
          :fit-block="false"
          class-name="someBetsGridBet__logo max-width_20 order_first margin-right_small parent_inline v-centered"
        />

        <span class="width_fill croped_line margin-top_small">{{ noteAboutEndMatch }}</span>
      </div>
      <!-- end someBetsGridBet -->

      <!-- begin someBetsGridStatus -->
      <span
        class="someBetsGridStatus parent h-centered  font-weight_semibold font-size_12-table"
        role="cell"
      >
        {{ betStatus }}
      </span>
      <!-- end someBetsGridStatus -->

      <!-- begin someBetsGridActions -->
      <div
        class="someBetsGridActions parent row"
        role="cell"
      >
        <base-button
          v-if="isButtonsShown.accept"
          :action="acceptBet"
          :micro-action="true"
          class-name="color_green someBetsGridActions__button someBetsGridActions__button_pay"
          label="Оплатить ставку"
        >$</base-button>
        <base-button
          v-if="isButtonsShown.cancel"
          :action="cancelBet"
          class-name="someBetsGridActions__button"
          label="Отменить ставку"
          micro-action
        >
          <lazy-image
            :fit-block="false"
            :src="returnIcon"
          />
        </base-button>

        <base-button
          v-if="isButtonsShown.delete"
          :action="deleteBet"
          class-name="someBetsGridActions__button"
          label="Удалить ставку"
          micro-action
        >
          <lazy-image
            :fit-block="false"
            :src="cancelIcon"
          />
        </base-button>
      </div>
    <!-- end someBetsGridActions -->

    </div>
    <!-- end someBetsTableRow -->
    <transition
      name="fadeTranslateToBottom"
    >

      <portal-target
        v-if="$store.state.personalRoom.user.statistic[statisticType].betPortalId === betPortalId"
        :name="betPortalId"
      />
    </transition>
    <slot/>
  </div>
</template>

<script>
import { cancel, previous } from "@/assets/images/icons";
import { requestUrls } from "@/constants/conf";
import { timeout, slideTo, ID } from "@/constants/pureFunctions";
import popupsNames from "@/constants/popups";

export default {
  name: "UserBet",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    betId: {
      type: [Number, String],
      required: true
    },
    gameImage: {
      type: String,
      required: true
    },
    matchDate: {
      type: [Date, String],
      required: true
    },
    leftTeamLogo: {
      type: String,
      required: true
    },
    leftTeamName: {
      type: String,
      required: true
    },
    rightTeamLogo: {
      type: String,
      required: true
    },
    rightTeamName: {
      type: String,
      required: true
    },

    winTeamLogo: {
      type: String,
      required: true
    },
    winTeamName: {
      type: String,
      required: true
    },
    noteAboutEndMatch: {
      type: String,
      required: true
    },
    betStatus: {
      type: [String, Boolean],
      required: true
    },
    betType: {
      type: [String, Boolean],
      required: true
    },
    sum: {
      type: [String, Number],
      required: true
    },
    acceptedSum: {
      type: [Number, Boolean, String],
      required: true
    },
    ratio: {
      type: [String, Number],
      required: true
    },
    isButtonsShown: {
      type: Object,
      required: false,
      default: () => ({
        accept: true,
        cancel: true,
        delete: true
      }),
      validator: function(buttonsState) {
        function checkBooleanButtonExistance(buttonName) {
          return (
            buttonName in buttonsState &&
            typeof buttonsState[buttonName] === "boolean"
          );
        }

        const buttonsNames = ["accept", "cancel", "delete"];

        return buttonsNames.every(checkBooleanButtonExistance);
      }
    }
  },
  data: () => ({
    isCountedForStatistic: false,
    returnIcon: previous,
    cancelIcon: cancel
  }),

  computed: {
    betPortalId() {
      return "portal_" + ID();
    },

    statisticType() {
      const statisticModule = {
        createdBet: "created",
        acceptedBet: "accepted"
      };
      const { betType } = this;

      return betType ? statisticModule[betType] : false;
    },

    moduleName() {
      let betType = this.betType;

      if (betType) {
        betType = betType[0].toUpperCase() + betType.slice(1);
      }

      return betType;
    }
  },
  beforeUpdate() {
    this.$nextTick(() => {
      const { statistic } = this.$store.state.personalRoom.user;
      const { statisticType } = this;
      const { betPortalId } = statistic[statisticType];

      if (this.isCountedForStatistic && this.betPortalId !== betPortalId) {
        this.isCountedForStatistic = false;
      }
    });
  },

  methods: {
    acceptBet() {
      this.emitBetActionEvent("accept-bet");
    },

    cancelBet() {
      this.emitBetActionEvent("cancel-bet");
    },

    deleteBet() {
      this.emitBetActionEvent("delete-bet");

      this.$store.commit("personalRoom/deleteUserBet", {
        id: this.betId,
        // this.betType is acceptedBet or createdBet.
        // User modules are acceptedBets and createdBets.
        moduleName: this.betType + "s"
      });
    },

    emitBetActionEvent(eventName) {
      this.$emit(eventName, {
        betId: this.betId,
        userId: this.$store.state.personalRoom.user.id
      });

      this.openMatchNotificationPopup();
    },

    openMatchNotificationPopup() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: true,
        popupName: popupsNames.matchNotification
      });
    },

    changeStatisticState() {
      this.$nextTick(() => {
        const {
          isCountedForStatistic,
          statisticType,
          ratio,
          acceptedSum,
          sum,
          betPortalId
        } = this;

        const choosenBetInfo = {
          newStatistic: {
            acceptedSum,
            sum,
            ratio,
            betPortalId
          },
          statisticType
        };

        this.isCountedForStatistic = !isCountedForStatistic;

        if (this.isCountedForStatistic) {
          this.$store.commit("personalRoom/showBetStatistic", choosenBetInfo);
        } else {
          this.$store.commit("personalRoom/hideBetStatistic", statisticType);
        }
      });
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'


.someBetsGridActions

  &__button

    &_pay
      font-size: 1.4em


.font-size_12-table
	font-size: em(12, 9.6)

.someBetsTableRow
  min-height: 80px


.someBetsTeam
    min-width: em(100, 12)

    &__title
      max-width: 100%

    &__image
      min-height: 1.66667em

.someBetsGridMatch

  &_row
    grid-template-columns: 1fr 30px 1fr
    grid-gap: 10px

.someBetsMatch

  &Versus
    text-shadow: 2px 2px 6px rgba(0,0,0,.2)
    font-size: em(22, 9.6)

.someBetsGridBet

  &__title
    max-width: 75%

.someBetsGridGameLogo
	max-width: em(13, 9.6)
</style>
