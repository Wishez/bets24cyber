<template>
  <div 
    :class="{
      'gameSorter parent row v-centered margin-left_auto': true,
    }"
  >
    <base-button 
      :action="changeGame('all')"
      :class-name="`gameSorter__button baseChild circle  parent centered ${$store.state[storeModuleId].activeGameId === 'all' ? 'button_blue' : 'button_gray'}`"
      label="Выбрать все игры"
      unstyled>
      <icon name="list-ul" />
    </base-button>
    
    <ul class="baseChild h-between v-centered games parent row">
      <li 
        v-for="(game, index) in games" 
        :key="index"
        :class="{
          'game margin-top_05': true,
          'game_active': game.id === $store.state[storeModuleId].activeGameId || $store.state[storeModuleId].activeGameId === 'all',
          'opacity_half': game.id !== $store.state[storeModuleId].activeGameId,

      }">
        <base-button
          :data-id="game.id"
          :action="changeGame(game.id)"
          :label="game.label" 
          unstyled
        >

          <lazy-image 
            :src="game.image" 
            :alt="game.label" 
            :title="game.label"
            relative
          />
	          
        </base-button>

      </li>
    </ul>
    <!-- end gameSorter -->
  </div>
</template>

<script>
import { csgo, dota, hs, lol, ow } from "@/assets/images/icons";

import { games, activeGamesIds } from "@/constants/conf";

// If you need async actions, then, you can use it
// https://github.com/localForage/localForage#callbacks-vs-promises

export default {
  name: "GameSorter",
  props: {
    storeModuleId: {
      type: String,
      required: true
    }
  },
  data: () => ({
    games: [
      {
        id: games.dota,
        image: dota,
        label: "Dota 2"
      },
      {
        id: games.csgo,
        image: csgo,
        label: "Csgo"
      },
      {
        id: games.lol,
        image: lol,
        label: "League of Legends"
      },
      {
        id: games.hs,
        image: hs,
        label: "Hearth Stone"
      },
      {
        id: games.ow,
        image: ow,
        label: "Owerwatch"
      }
    ]
  }),
  methods: {
    showAllGames() {
      this.changeGame("all")();
    },
    changeGame(choosenGameId, module) {
      // Каррирую функцию, чтобы не было проблем
      // с выбором узла.
      return () => {
        this.$store.commit(`${this.storeModuleId}/switchGameId`, choosenGameId);

        localStorage[activeGamesIds[this.storeModuleId]] = choosenGameId;
      };
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_easing.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'

.games
  min-width: em(190)
.gameSorter
  will-change: transform
  transition: transform .3s $sharp
  &_shown
    transform: translateX(0)
  &_hidden
    transform: translateX(10em)

  min-width: em(235)
  &__button
    max-width: em(30)
    min-height: em(30)
    height: 1px
    margin-right: .9em
.game
  padding: 0
  filter: grayscale(1)
  // opacity: .5
  will-change: filter, opacity, transform
  transition: filter .2s $sharp, opacity .2s $sharp, transform .2s $sharp
  max-width: 27px
  &:hover
    transform: scale(1.04)
  &:hover, &_active
    opacity: 1
  &_active
    filter: grayscale(0)
</style>
