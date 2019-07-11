<template>
  <modal-container
    :on-close-modal="closePopup"
    :is-litter-fit-content="false"
  >
    <article
      class="popupMatch"
    >
      <bets-match-popup-header
        :league-name="bet.league.name"
        :league-stage="bet.league.stage"
        :match-date="bet.match.date"
      />
      <!-- Декоративные элементы -->
      <bets-match-popup-labels />
      <!-- begin matchStatus -->
      <div class="matchStatus">

        <bets-match-popup-bets-header />
        <!-- Конец декоративных элементов -->


        <team-status-bar
          :team-name="bet.match.status.teamName"
          :team-status="bet.match.status.state"
          :team-logo="bet.match.status.logo"
          :accept-ratio1="bet.match.status.ratio1"
          :accept-ratio2="bet.match.status.ratio2"
          :accept-ratio3="bet.match.status.ratio3"
        />

        <team-status-bar
          :team-logo="bet.match.status.logo"
          :accept-ratio1="1"
          :accept-ratio2="2"
          :accept-ratio3="3"
          team-name="Team Name"
          team-status="Поражение"
        />

        <team-status-bar
          :team-logo="drawImage"
          :accept-ratio1="1.618"
          :accept-ratio2="1.4"
          :accept-ratio3="1.2"
          team-name=""
          team-status="Ничья"
        />

      </div>
      <!-- end matchStatus -->

    </article>
    <!-- end meta -->

  </modal-container>
</template>

<script>
import { cup } from "@/assets/images/icons";

import BetsMatchPopupHeader from "@/components/BetsMatchPopupHeader";
import BetsMatchPopupBetsHeader from "@/components/BetsMatchPopupBetsHeader";
import BetsMatchPopupLabels from "@/components/BetsMatchPopupLabels";
import TeamStatusBar from "@/components/TeamStatusBar";

export default {
  name: "BetsMatchPopup",
  components: {
    TeamStatusBar,
    BetsMatchPopupHeader,
    BetsMatchPopupBetsHeader,
    BetsMatchPopupLabels
  },
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    }
  },
  data: () => ({
    drawImage: cup
  }),
  computed: {
    bet() {
      return this.$store.state.bets.bet;
    }
  },
  methods: {
    closePopup() {
      this.$store.dispatch("bets/closePopupAndCleanState");
    }
  }
};
</script>

<style lang="sass" scoped>
  @import '../assets/sass/conf/_colors.sass'
  @import '../assets/sass/conf/_sizes.sass'
  @import '../assets/sass/conf/_breakpoints.sass'
  @import '../assets/sass/conf/_easing.sass'

  // .matchStatus
  //   @include breakpoint($tablet-less)
  //     margin-bottom: 3em

</style>
