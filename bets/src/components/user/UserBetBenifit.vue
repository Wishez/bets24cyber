<template>
  <!-- begin userChoosenBet -->
  <div class="userChoosenBet display_grid">
    <user-bet 
      :bet-id="betId"
      :game-image="gameImage"
      :left-team-logo="leftTeamLogo"
      :win-team-logo="winTeamLogo"
      :right-team-logo="rightTeamLogo"
      :bet-status="betStatus"
      :match-date="matchDate"
      :win-team-name="winTeamName"
      :left-team-name="leftTeamName"
      :right-team-name="rightTeamName"
      :note-about-end-game="noteAboutEndGame"
      class-name="font-size_10"
    />
 
    <!-- begin userChoosenBetInfo -->
    <dl class="userSomeBetsSorterContainer userChoosenBetInfo parent row tableHead v-centered font-size_11">
      <dt class="userChoosenBetInfo__title ">Коэффициент:</dt> 
      <dd class="userChoosenBetInfo__info color_white parent centered">{{ ratio }}</dd>
      <dt class="userChoosenBetInfo__title ">Сумма:</dt>
      <dd class="userChoosenBetInfo__info color_white parent centered">$ {{ sum }}</dd>

      <dt class="userChoosenBetInfo__title">Принятые ставки:</dt> 
      <dd class="userChoosenBetInfo__info color_white parent centered">$ {{ acceptedBetsSum }}</dd>

      <dt class="userChoosenBetInfo__title">Возможный выигрыш:</dt> 
      <dd class="userChoosenBetInfo__info color_white parent centered">$ {{ possibleWinSum }}</dd>
    </dl>
    <!-- end userChoosenBetInfo -->
              
  </div>
  <!-- end userChoosenBet -->
</template>

<script>
import UserBet from '@/components/user/UserBet';
export default {
  name: 'UserChoosenBet',
  components: {
    UserBet,
  },
  components: {},
  mixins: [],
  props: {
    possibleWinSum: {
      type: Number,
      required: true,
    },
    sum: {
      type: Number,
      required: true,
    },
    acceptedBetsSum: {
      type: Number,
      required: true,
    },
    ratio: {
      type: Number,
      required: true,
    },
    betId: {
      type: [Number, String],
      required: true,
    },
    gameImage: {
      type: String,
      required: true,
    },
    matchDate: {
      type: [Date, String],
      required: true,
    },
    leftTeamLogo: {
      type: String,
      required: true,
    },
    leftTeamName: {
      type: String,
      required: true,
    },
    rightTeamLogo: {
      type: String,
      required: true,
    },
    rightTeamName: {
      type: String,
      required: true,
    },

    winTeamLogo: {
      type: String,
      required: true,
    },
    winTeamName: {
      type: String,
      required: true,
    },
    noteAboutEndGame: {
      type: String,
      required: true,
    },
    betStatus: {
      type: [String, Boolean],
      required: true,
    },
  },
};
</script>

<style lang="sass" scoped>
	
</style>
