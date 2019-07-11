<template>
  <!-- begin teams -->
  <div :class="`relative teams wrap blockOffsets grow parent row height_fill v-centered margin_vertical-small-phone ${className}`">

    <!-- begin team team_left -->
    <div
      :class="[
        'width_fill-phone  team team_left parent row v-centered height_fill grow margin_vertical-small-phone row_reverse-phone row_reverse-phone',
        namesBeneathLogo ? 'h-around' : 'h-end',
        leftTeamClass
    ]">
      <!-- begin teamName -->
      <div
        v-if="!namesBeneathLogo"
        :class="`child team__name text_right font-size_11 ${teamNameClass}`">
        {{ leftTeamName }}
      </div>
      <!-- end teamName -->

      <!-- begin teamLogo -->
      <div :class="`child team__logo parent column centered ${teamLogoContainerClass}`">
        <lazy-image
          :src="leftTeamLogo"
          :class-name="`team__logo_image margin_centered ${teamLogoClass} ${leftTeamLogoClass}`"
          relative/>
        <span
          v-if="namesBeneathLogo"
          :class="`team__name text_centered font-weight_semibold ${teamNameClass} ${leftTeamNameClass}`">
          {{ leftTeamName }}
        </span>
      </div>
      <!-- end teamLogo -->

      <!-- begin teamCountry -->
      <div
        :class="{
          'child team__country': true ,
          'order_first': namesBeneathLogo
      }">
        <lazy-image
          :src="leftTeamCountry"
          :class-name="`parent centered ${teamCountryClass} ${leftTeamCountry}`"
          circle
          relative/>

      </div>
      <!-- end teamLogo -->

      <!-- begin teamRatio -->
      <div :class="`child team__ratio relative ${ratioContainerClass} ${leftTeamRatioClass}`">
        <p 
          :class="{
            'parent centered team__ratio_info margin-top_zero margin_centered' : true,
            ratio_info: true,
            ratio_info_red: isArrowsShown && !isLeftRatioArrowUp,
            ratio_info_green: isArrowsShown && isLeftRatioArrowUp,
            'team__ratio_info-size-9' : isSmallRatio,
            [teamRatioClass]: teamRatioClass
        }">
          {{ leftTeamRatio | fixSum }}

          <transition name="fading">          
            <svg 
              v-if="isArrowsShown"
              :class="{
                ratio_arrow: true,
                [`ratio_arrow_upFix color_green`]: isLeftRatioArrowUp,
                [`ratio_arrow_downFix color_red`]: isArrowsShown && !isLeftRatioArrowUp
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
      <!-- end teamRatio -->

    </div>
    <!-- end teamLeft -->

    <!-- begin versus -->
    <div 
      v-if="series" 
      class="series parent column centered absolute position_base width_fill">

      <svg 
        aria-hidden="true" 
        role="img" 
        xmlns="http://www.w3.org/2000/svg" 
        viewBox="0 0 576 512" 
        width="1em" 
        height="1em"
        class="margin-bottom_quarter color_yellow"
      >
        <path 
          fill="currentColor" 
          d="M552 64H448V24c0-13.3-10.7-24-24-24H152c-13.3 0-24 10.7-24 24v40H24C10.7 64 0 74.7 0 88v56c0 35.7 22.5 72.4 61.9 100.7 31.5 22.7 69.8 37.1 110 41.7C203.3 338.5 240 360 240 360v72h-48c-35.3 0-64 20.7-64 56v12c0 6.6 5.4 12 12 12h296c6.6 0 12-5.4 12-12v-12c0-35.3-28.7-56-64-56h-48v-72s36.7-21.5 68.1-73.6c40.3-4.6 78.6-19 110-41.7 39.3-28.3 61.9-65 61.9-100.7V88c0-13.3-10.7-24-24-24zM99.3 192.8C74.9 175.2 64 155.6 64 144v-16h64.2c1 32.6 5.8 61.2 12.8 86.2-15.1-5.2-29.2-12.4-41.7-21.4zM512 144c0 16.1-17.7 36.1-35.3 48.8-12.5 9-26.7 16.2-41.8 21.4 7-25 11.8-53.6 12.8-86.2H512v16z"/>
      </svg>
      <span class="cropped_line">{{ series }}</span>
    </div>
    <div
      :class="{'child versus text_centered font-weight_bold color_gray grow margin_centered-phone margin_vertical-small-phone' : true,
               'versus_size-18': isBigVersus,
               [versusClass]: versusClass

    }">
      {{ isCompleted ? `${matchScore.left}:${matchScore.right}`: 'VS' }}
    </div>
    <!-- end versus -->

    <!-- begin team team_right -->
    <div
      :class="[
        'width_fill-phone team team_right parent row v-centered height_fill grow margin_vertical-small-phone',
        namesBeneathLogo ? 'h-around' : null,
        rightTeamClass
    ]">

      <!-- begin teamRatio -->
      <div :class="`child team__ratio relative ${ratioContainerClass} ${rightTeamRatioClass}`">
        <p
          :class="{
            'parent centered margin-top_zero margin_centered' : true,
            'team__ratio_info-size-9' : isSmallRatio,
            [teamRatioClass]: teamRatioClass,
            ratio_info: true,
            ratio_info_red: isArrowsShown && !isRightRatioArrowUp,
            ratio_info_green: isArrowsShown && isRightRatioArrowUp 
        }">
          {{ rightTeamRatio | fixSum }}

          <transition name="fading">          
            <svg 
              v-if="isArrowsShown"
              :class="{
                ratio_arrow: true,
                [`ratio_arrow_upFix color_green`]: isRightRatioArrowUp,
                [`ratio_arrow_downFix color_red`]: isArrowsShown && !isRightRatioArrowUp
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
      <!-- end teamRatio -->

      <!-- begin teamCountry -->
      <div
        :class="{
          'child team__country': true ,
          'order_last': namesBeneathLogo
      }">
        <lazy-image
          :src="rightTeamCountry"
          :class-name="`parent centered ${teamCountryClass} ${rightTeamCountryClass}`"
          circle
          relative/>

      </div>
      <!-- end teamCountry -->

      <!-- begin teamLogo -->
      <div
        :class="`child team__logo parent column centered ${teamLogoContainerClass}`">
        <lazy-image
          :src="rightTeamLogo"
          :class-name="`team__logo_image margin_centered ${teamLogoClass} ${leftTeamLogoClass}`"
          relative/>
        <span
          v-if="namesBeneathLogo"
          :class="`team__name text_centered font-weight_semibold ${teamNameClass}`">
          {{ rightTeamName }}
        </span>
      </div>
      <!-- end teamLogo -->

      <!-- begin teamName -->
      <div
        v-if="!namesBeneathLogo"
        :class="`child team__name text_left font-size_11 ${teamNameClass}`">
        {{ rightTeamName }}
      </div>
      <!-- end teamName -->
    </div>
    <!-- end team team_right -->

    <!-- begin watchMatch -->
    <div
      v-if="withOpenMatchButton"
      class="child  watchMatch parent centered margin_vertical-small-phone margin_centered-phone">
      <base-button
        :label="`Открыть просмотр матча ${leftTeamName} против ${rightTeamName}.`"
        class-name="parent centered bordered_darkBlue bordered grow_phone margin_base-phone watchMatch__button color_darkBlue"
        unstyled>
        <svg
          class="fa-icon"
          height="1em"
          role="presentation"
          version="1.1"
          viewBox="0 0 1792 1792"
          width="1em">
          <path d="M711 1128l484-250-484-253v503zM896 266q168 0 324.5 4.5t229.5 9.5l73 4q1 0 17 1.5t23 3 23.5 4.5 28.5 8 28 13 31 19.5 29 26.5q6 6 15.5 18.5t29 58.5 26.5 101q8 64 12.5 136.5t5.5 113.5v40 136q1 145-18 290-7 55-25 99.5t-32 61.5l-14 17q-14 15-29 26.5t-31 19-28 12.5-28.5 8-24 4.5-23 3-16.5 1.5q-251 19-627 19-207-2-359.5-6.5t-200.5-7.5l-49-4-36-4q-36-5-54.5-10t-51-21-56.5-41q-6-6-15.5-18.5t-29-58.5-26.5-101q-8-64-12.5-136.5t-5.5-113.5v-40-136q-1-145 18-290 7-55 25-99.5t32-61.5l14-17q14-15 29-26.5t31-19.5 28-13 28.5-8 23.5-4.5 23-3 17-1.5q251-18 627-18z"/>
        </svg>
      </base-button>
    </div>
    <!-- end watchMatch -->

  </div>
  <!-- end teams -->
</template>

<script>
import { showChangingRatio } from "@/constants/ratio";

export default {
  name: "MatchTeams",
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
    series: {
      type: String,
      required: false,
      default: ""
    },

    namesBeneathLogo: {
      type: Boolean,
      required: false,
      default: false
    },
    isBigVersus: {
      type: Boolean,
      required: false,
      default: false
    },
    isSmallRatio: {
      type: Boolean,
      required: false,
      default: false
    },
    withOpenMatchButton: {
      type: Boolean,
      required: false,
      default: false
    },
    teamLogoClass: {
      type: String,
      required: false,
      default: ""
    },
    teamNameClass: {
      type: String,
      required: false,
      default: ""
    },
    rightTeamClass: {
      type: String,
      required: false,
      default: ""
    },
    teamLogoContainerClass: {
      type: String,
      required: false,
      default: ""
    },
    rightTeamCountryClass: {
      type: String,
      required: false,
      default: ""
    },
    rightTeamLogoClass: {
      type: String,
      required: false,
      default: ""
    },
    rightTeamRatioClass: {
      type: String,
      required: false,
      default: ""
    },
    leftTeamClass: {
      type: String,
      required: false,
      default: ""
    },
    leftTeamCountryClass: {
      type: String,
      required: false,
      default: ""
    },
    leftTeamLogoClass: {
      type: String,
      required: false,
      default: ""
    },
    teamRatioClass: {
      type: String,
      required: false,
      default: ""
    },
    leftTeamRatioClass: {
      type: String,
      required: false,
      default: ""
    },
    leftTeamNameClass: {
      type: String,
      required: false,
      default: ""
    },
    ratioContainerClass: {
      type: String,
      required: false,
      default: ""
    },
    versusClass: {
      type: String,
      required: false,
      default: ""
    },
    teamCountryClass: {
      type: String,
      required: false,
      default: ""
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
      type: [Number, String],
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
    matchUrl: {
      type: String,
      required: true
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
    }
  },
  data: () => ({
    isRightRatioArrowUp: false,
    isLeftRatioArrowUp: false,
    isArrowsShown: false
  }),
  mounted() {
    showChangingRatio.call(this);
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'
@import '../assets/sass/conf/_easing.sass'

.series
  top: 12px

  @include breakpoint($tablet-less)
    top: 50%
    right: 10%
    min-width: auto
    left: auto
    transform: translateY(-100%)
  
  @include breakpoint($phone-less)
    right: auto
    left: 7%
    transform: translateY(-30%)

.teams
  @include breakpoint($betsTabletLess)
    margin-top: .5em
    margin-bottom: 1.5em


.child
	flex: 1 0 auto
.team
	@include breakpoint($phone-less)
		justify-content: space-around
 	// Контейнер
	&__logo
		min-width: em(40, 12)
		max-width: em(65, 12)

		// Изображение
		&_image
	    	max-width: em(30, 12)

	&__name
		min-width: em(90, 11)
		max-width: em(90, 11)
		@include breakpoint($phone-less)
			text-align:  left

	// Контейнер
	&__ratio
		max-width: em(50, 12)

		// Соотношение
		&_info
      max-width: em(32, 12)
      padding: .5em 0.15em .5em 0
      
      &-size-9
        font-size: em(9, 12)

	&__country
		max-width: em(27.5, 12)

		.imageContainer
			max-width: em(27.5, 12)
			min-height: em(27.5, 12)

.watchMatch
	@include breakpoint($tablet-less)
    	max-width: em(270, 12)

	&__button
    opacity: .3
    font-size: em(16, 12)
    will-change: opacity, color
    transition-property: color, opacity
    transition-timing-function: $sharp

    &:active, &:focus, &:hover
      color: $blue
      opacity: 1

    @include breakpoint($tablet)
        padding: .25em
        border-width: 1px $i

    @include breakpoint($desktop)
        border-width: 0 $i

    @include breakpoint($tablet-less)
        padding: .25em
        border-width: 1px $i
        max-width: em(100)
.versus
  max-width: em(29, 12)
  color: #989ba4
  &_size-18
    font-size: em(18, 12)
</style>
