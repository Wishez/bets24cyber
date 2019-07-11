<template>
  <article
    :class="{
      'matchContainer highlightRatioOnHover': true,
      [className]: className,
    }"
    @click="openMatch"
  >

    <!-- begin matchInfo -->
    <div class="matchInfo  margin-top_33 relative parent row wrap shadow_spread">

      <h3 class="matchInfo__title font-weight_semibold font-size_12 text_centered width_fill croped_line dispaly_block">{{ leagueName }}</h3>
      <h4 class="matchInfo__subtitle font-weight_light font-size_10 text_centered  width_fill croped_line dispaly_block">{{ leagueStage }}</h4>

      <!-- begin team -->
      <div class="team centered parent column">
        <h4 class="team__title croped_line text_centered font-size_14 font-weight_semibold">{{ leftTeamName }}</h4>
        <lazy-image
          :src="leftTeamLogo"
          class-name="team__logo order_first parent centered"
          relative/>

        <p class="font-size_12 ratio min-width_30">
          <span 
            :class="{
              ['ratio_info parent centered width_fill']: true,
              ratio_info_red: isArrowsShown && !isLeftRatioArrowUp,
              ratio_info_green: isArrowsShown && isLeftRatioArrowUp 
          }">{{ leftTeamRatio | fixSum }}</span>
          <transition name="fading">
            <svg 
              v-if="isArrowsShown"
              :class="{
                ratio_arrow: true,
                [`ratio_arrow_up color_green`]: isLeftRatioArrowUp,
                [`ratio_arrow_down color_red`]: isArrowsShown && !isLeftRatioArrowUp
              }"
              viewBox="0 0 100 100" 
              aria-hidden="true" 
              role="img"
            >
              <use xlink:href="#arrowIcon"/>
            </svg>
          </transition>
        </p>
      </div>
      <!-- end team -->

      <!-- begin matchMeta -->
      <div class="grow matchMeta parent column v-centered">
        <time
          :datetime="matchDate | formatDate('L')"
          class="parent column centered matchMetaTime">
          <span class="dayAndMonth font-size_10 font-weight_semibold">{{ matchDate | formatDate("D MMMM") }}</span>
          <span class="time">{{ matchDate | formatDate("HH:mm") }}</span>
        </time>
        <lazy-image
          :src="gameImage ? gameImage : gameImages.gameLogo"
          class-name="padding-top_small matchMeta__gameLogo parent h-centered relative order_first v-start"
          relative
          fit
        />
      </div>
      <!-- end matchMeta -->

      <!-- begin team -->
      <div class="team grow centered parent column">
        <h4 class="team__title croped_line text_centered font-size_14 font-weight_semibold">{{ rightTeamName }}</h4>
        <lazy-image
          :src="rightTeamLogo"
          class-name="team__logo order_first parent centered"
          relative />
        <p class="font-size_12 ratio min-width_30">
          <span 
            :class="{
              ['ratio_info parent centered width_fill']: true,
              ratio_info_red: isArrowsShown && !isRightRatioArrowUp,
              ratio_info_green: isArrowsShown && isRightRatioArrowUp 
          }">{{ rightTeamRatio | fixSum }}</span>

          <transition name="fading">          
            <svg 
              v-if="isArrowsShown"
              :class="{
                ratio_arrow: true,
                [`ratio_arrow_up color_green`]: isRightRatioArrowUp,
                [`ratio_arrow_down color_red`]: isArrowsShown && !isRightRatioArrowUp
              }"
              viewBox="0 0 100 100" 
              aria-hidden="true" 
              role="img"
            >
              <use xlink:href="#arrowIcon"/>
            </svg>
          </transition>
        </p>
      </div>
      <!-- end team -->
      <lazy-image
        :src="gameBackground ? gameBackground : gameImages.gameBackground"
        class-name="position_base"
        is-image-fill-container
        absolute/>
    </div>
    <!-- end matchInfo -->
  </article>
</template>

<script>
import { showChangingRatio } from "@/constants/ratio";

import { csgo, dota, hs, lol, ow } from "@/assets/images/icons";
import {
  csgo_bg,
  dota_bg,
  hs_bg,
  lol_bg,
  ow_bg
} from "@/assets/images/backgrounds";
import { games } from "@/constants/conf";

export default {
  name: "MatchInfo",
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    lastRatioValues: {
      type: Object,
      required: false,
      default: () => ({
        left: -1,
        right: -1
      }),
      validate: function(lastRatioValues) {
        return "left" in lastRatioValues && "right" in lastRatioValues;
      }
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    leagueName: {
      type: String,
      required: true
    },
    leagueStage: {
      type: String,
      required: false,
      default: "Group Stage"
    },
    leftTeamName: {
      type: String,
      required: true
    },
    leftTeamLogo: {
      type: String,
      required: true
    },
    leftTeamRatio: {
      type: Number,
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
      type: Number,
      required: true
    },
    matchDate: {
      type: [Date, String],
      required: true
    },
    gameId: {
      type: String,
      required: true
    },
    gameImage: {
      type: [String, Boolean],
      required: true
    },
    gameBackground: {
      type: [String, Boolean],
      required: true
    },
    matchUrl: {
      type: String,
      required: true
    }
  },
  data: () => ({
    isRightRatioArrowUp: false,
    isLeftRatioArrowUp: false,
    isArrowsShown: false
  }),
  computed: {
    gameImages() {
      let gameLogo = "";
      let gameBackground = "";

      switch (this.gameId) {
        case games.dota:
          gameLogo = dota;
          gameBackground = dota_bg;
          break;
        case games.csgo:
          gameLogo = csgo;
          gameBackground = csgo_bg;
          break;
        case games.hs:
          gameLogo = hs;
          gameBackground = hs_bg;
          break;
        case games.lol:
          gameLogo = lol;
          gameBackground = lol_bg;
          break;
        case games.ow:
          gameLogo = ow;
          gameBackground = ow_bg;
          break;
        default:
      }

      return {
        gameLogo: this.gameImage || gameLogo,
        gameBackground: this.gameBackground || gameBackground
      };
    }
  },

  mounted() {
    showChangingRatio.call(this);
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
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_easing.sass'

.min-width_30
  min-width: 30px
  
.matchContainer
  max-width: em(262.5)
  display: inline-block

.matchInfo
  padding: em(10) em(14) 1.5em em(25)
  margin-bottom: em(21)
  min-height: em(157)

  will-change: transform
  transition: 270ms transform $sharp

  &:hover, &:focus
    transform: scale(1.05)




.matchInfo__subtitle
  margin-bottom: em(7)

.team
  max-width: 50px
  min-height: 87px

  &__title
    max-width: 75px
    min-height: 1.4em

  &__logo
    max-width: 48px
    max-height: 37px
    margin-bottom: em(11)

.matchMeta
  margin: 0 em(18)
  max-width: em(70)

  &__gameLogo
    max-width: em(17)
    max-height: em(50)

</style>
