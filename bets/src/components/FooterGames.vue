<template>
  <div class="footerGames parent row wrap grow v-end margin-top_base-phone">
    <lazy-image 
      v-for="(game, index) in games"
      :key="index"
      :alt="game.name" 
      :src="game.image"
      :title="game.name"
      :is-image-fill-container="false"
      class-name="footerGame parent centered width_auto"
      relative
    />
  </div>
</template>

<script>
import { csgo, dota, hs, lol, ow } from "@/assets/images/logos";
export default {
  name: "FooterGames",
  mixins: [],
  data: () => ({
    games: [
      {
        name: "Dota 2",
        image: dota
      },
      {
        name: "Cs Go",
        image: csgo
      },
      {
        name: "League of Legends",
        image: lol
      },
      {
        name: "Hearth Stone",
        image: hs
      },
      {
        name: "Owerwatch",
        image: ow
      }
    ]
  })
};
</script>

<style lang="sass" scoped>
@import '../assets/sass/conf/_colors.sass'
@import '../assets/sass/conf/_easing.sass'
@import '../assets/sass/conf/_sizes.sass'
@import '../assets/sass/conf/_breakpoints.sass'

.footerGames
  @include breakpoint(max-width em($containerWidth))
    justify-content: flex-end
.footerGame
  padding: 0 em(11)
  max-width: em(100)
  @include breakpoint($phone-less)
    max-width: 20%
  &:first-of-type
    padding-left: 0
</style>
