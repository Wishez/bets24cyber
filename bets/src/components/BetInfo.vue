<template>
  <div
    ref="betInfo"
    class="font-weight_semibold tableContentRow highlightRatioOnHover parent row wrap v-centered h-around margin_centered-phone"
    @click="openMatch"
  >
    <!-- begin league -->
    <div class="league wrap blockOffsets grow v-centered height_fill parent row nowrap_tablet">
      <transition name="fading">
        <svg 
          v-if="isOnline"
          viewBox="0 0 100 100" 
          width="1em" 
          height="1em" 
          class="color_green league__indicator">
          <circle 
            cy="50" 
            cx="50" 
            fill="currentColor" 
            r="50"/>
        </svg>
      </transition>

      <lazy-image
        :src="leagueLogo"
        :is-image-fill-container="false"
        class-name="tableContentCeil tableContentCeil_leagueLogo parent h-end v-centered"
        image-classes="margin-left_auto margin-right_auto-xs max-width_45"
        relative
      />
      <h3
        class="tableContentCeil tableContentCeil_leagueName text_centered-phone font-weight_semibold font-size_16 croped_line">
        {{ leagueName }}
      </h3>

      <time
        :datetime="matchDate | formatDate('L HH:mm')"
        class="tableContentCeil tableContentCeil_date margin-top_base-phone font-size_11">
        {{ matchDate | formatDate("D MMMM") }}
      </time>

      <time
        :datetime="matchDate | formatDate('L HH:mm')"
        class="tableContentCeil tableContentCeil_time color_blue font-size_11 margin-top_base-phone">
        {{ matchDate | formatDate("HH:mm") }}
      </time>
    </div>
    <!-- end league -->

    <!-- begin teams -->
    <match-teams
      :left-team-ratio="leftTeamRatio"
      :left-team-name="leftTeamName"
      :left-team-logo="leftTeamLogo"
      :left-team-country="leftTeamCountry"
      :right-team-ratio="rightTeamRatio"
      :right-team-name="rightTeamName"
      :right-team-logo="rightTeamLogo"
      :right-team-country="rightTeamCountry"
      :match-url="matchUrl"
      :last-ratio-values="lastRatioValues"
      :is-completed="isCompleted"
      :match-score="matchScore"
      with-open-match-button
    />
    <!-- end teams -->
  </div>
</template>

<script>
import MatchTeams from "@/components/MatchTeams";

export default {
  name: "BetInfo",
  components: {
    MatchTeams
  },

  props: {
    isOnline: {
      type: Boolean,
      required: true
    },
    isCompleted: {
      type: Boolean,
      required: false,
      default: false
    },
    matchScore: {
      type: Object,
      required: false,
      default: () => ({ left: 0, right: 0 }),
      validate: score => {
        function isNumber(property) {
          return property in score && typeof property === "number";
        }
        return isNumber("left") && isNumber("right");
      }
    },
    lastRatioValues: {
      type: Object,
      required: false,
      default: () => ({
        left: -1,
        right: -1
      })
    },
    leagueName: {
      type: String,
      required: true
    },
    leagueLogo: {
      type: String,
      required: true
    },
    leftTeamName: {
      type: String,
      required: true
    },
    leftTeamLogo: {
      type: String,
      required: true
    },
    leftTeamCountry: {
      type: String,
      required: true
    },
    leftTeamRatio: {
      type: [Number, String],
      required: true
    },
    rightTeamName: {
      type: String,
      required: true
    },
    rightTeamLogo: {
      type: String,
      required: true
    },
    rightTeamRatio: {
      type: [Number, String],
      required: true
    },
    rightTeamCountry: {
      type: String,
      required: true
    },
    matchDate: {
      type: [String, Date],
      required: true
    },
    matchUrl: {
      type: String,
      required: true
    }
  },
  mounted() {
    this.$bus.$on("update-timezone", () => {
      this.$forceUpdate();
    });
  },

  destroyed() {
    this.$bus.$off("update-timezone");
  },

  methods: {
    openMatch() {
      this.$store.dispatch("popups/openMatch", this.matchUrl);
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/colors'
@import '../assets/sass/conf/sizes'
@import '../assets/sass/conf/breakpoints'
@import '../assets/sass/conf/easing'

.league__indicator
  
  @include breakpoint($phone)
    transform: translateX(4em)

  @include breakpoint($phone-less)
    transform: translateX(1em)
  
.tableContentRow
  &:hover
    background-color: #f4f3f3

.nowrap_tablet
  @include breakpoint($tablet)
      flex-wrap: nowrap
.tableContentCeil
  flex: 1 0 auto

  &_leagueLogo

    @include breakpoint($phone)
      max-width: em(160, 12)

    @include breakpoint($phone-less)
      order: -1

    min-width: em(120, 12)
    padding-right: 1.3em

    @include breakpoint($phone-less)
      max-width: 45%
      justify-content: center


  &_leagueName
    max-width: em(236, 12)
    
    @include breakpoint($desktop)
      min-width: em(236, 12) //206

    @include breakpoint($desktop-less)
      min-width: em(128, 12)

    @include breakpoint($phone-less)
      min-width: 55%
      max-width: 185px
      padding-right: 1rem


  &_date
    max-width: em(64, 11)

  &_time
    max-width: em(43, 11)

.blockOffsets
  @include breakpoint($betsTabletLess)

.league
  @include breakpoint($betsTabletLess)
    margin-top: 1em
    margin-bottom: 1em

.font-size_11
  font-size: em(11, 12)
</style>
