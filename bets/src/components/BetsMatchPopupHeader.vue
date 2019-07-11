<template>
  <!-- begin matchHeader -->
  <div 
    v-lazy:background-image="cup_bg"
    class="popupMatchHeader popupMatchHeaderGrid shadow_spread display_grid font-size_12"
  >        
    <!-- begin matchMeta -->
    <div class="grow matchMeta parent row wrap_phone">
          
      <h2 class="lagueName padding-left_half-quarter parent column font-weight_semibold line-height_3 width_fill-phone">
        {{ leagueName }}
        <span class="lagueStage">{{ leagueStage }}</span>
      </h2>

      <time 
        :datetime="matchDate | formatDate('L')"
        class="matchDate padding-right_base padding-top_quarter order_first parent column relative after after_absolute row_phone h-end_phone v-centered_phone padding-bottom_small-phone width_fill-phone"
      >
        <span class="matchDate__date">
          {{ matchDate | formatDate("D MMMM") }}
        </span>
        <span class="matchDate__time margin-left_quarter-phone">
          {{ matchDate | formatDate("HH:mm") }}
          
        </span>
      </time>
    </div>
    <!-- end matchMeta -->
    <slot />
  </div>
  <!-- end matchHeader -->
</template>

<script>
import MatchTeams from "@/components/MatchTeams";
import MatchStreams from "@/components/MatchStreams";

import { transformDate } from "@/constants/pureFunctions";
import { cup_bg } from "@/assets/images/backgrounds";

export default {
  name: "BetsMatchPopupHeader",
  props: {
    leagueName: {
      type: String,
      required: true
    },
    leagueStage: {
      type: String,
      required: true
    },
    matchDate: {
      type: [String, Date],
      required: true
    }
  },
  data: () => ({
    cup_bg
  }),

  methods: {
    getFormatedTime(format) {
      return transformDate(this.matchDate, format);
    }
  }
};
</script>

<style lang="scss" scoped>
@import "../assets/sass/conf/_colors.sass";
@import "../assets/sass/conf/_sizes.sass";
@import "../assets/sass/conf/_breakpoints.sass";
@import "../assets/sass/conf/_easing.sass";

.matchDate {
  @include breakpoint($phone-less) {
    order: 2;
  }
  &:after {
    top: 8px;
    right: 0;
    width: 1px;
    height: 100%;
    background-color: #ccc;
    max-height: 34px;
    @include breakpoint($phone-less) {
      top: auto;
      bottom: 0;

      width: 100%;
      height: 1px;
    }
  }

  &__date {
    font-size: em(11, 12);
  }

  &__time {
    font-size: em(20, 12);
    min-width: 52px;
  }
}
.lagueName {
  font-size: em(16, 12);
  padding-top: 0.2em;
}
.matchMeta {
  grid-area: meta;
}
.matchStatus {
  &Results {
    margin-left: em(-18, 10);
  }
}

.popupMatchHeader {
  padding: em(20, 12) 2em 0 em(25, 12);
  background-repeat: no-repeat;
  background-size: cover;

  grid-gap: 0 22px;
  grid-template-areas: "meta meta stream" "teams teams stream";

  @include breakpoint($phone) {
    margin-bottom: em(29, 12);
    grid-template-columns: 1fr 1fr 230px;
    grid-template-rows: 46px 250px;
  }

  @include breakpoint($phone-less) {
    padding-bottom: em(29, 12);
  }

  @include breakpoint($tablet) {
    grid-template-rows: 46px 148px;
  }

  @include breakpoint($phone-less) {
    grid-gap: 15px 10px;
    grid-template-areas: "meta" "teams" "stream";

    grid-template-rows: 70px 175px 1fr;
  }
}

.popupMatchTeams {
  grid-area: teams;
  font-size: 12px;

  @include breakpoint($tablet-less) {
    display: grid;
    grid-template-rows: 100px 29px 75px;
    grid-template-columns: 100%;
  }

  @include breakpoint($phone-less) {
    grid-template-rows: repeat(3, 1fr);
  }
}

.popupMatchStreams {
  grid-area: stream;
}
</style>
