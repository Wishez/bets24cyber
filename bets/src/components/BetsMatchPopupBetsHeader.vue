<template>
  <!-- begin userBetsHeader -->
  <div class="tableHead growChilds matchBetGrid matchBetGrid_singleLine-tablet parent row font-size_10 h-around">

    <!-- begin matchStatus__results -->
    <div class="matchStatusResults text_centered">
      Результаты
    </div>
    <!-- end matchStatus__results -->
    <!-- begin offerBet -->
    <div class="growChilds translateX_large-quarter-tablet offerBet parent row h-around">

      <!-- begin matchStatus__bet -->
      <div 
        id="offerBetLabel" 
        class="matchStatus__bet">
        Ставка
      </div>
      <!-- end matchStatus__bet -->
          
      <!-- begin matchStatus__ratio -->
      <div 
        id="offerRatioLabel" 
        class="matchStatus__ratio">
        Коэф-т.
      </div>
      <!-- end matchStatus__ratio -->

      <!-- begin matchStatus__win -->
      <div class="matchStatus__win">
        Выигрыш
      </div>

      <!-- end matchStatus__win -->
    </div>
    <!-- end offetBet -->

        
    <!-- begin acceptBet -->
    <div class="growChilds display_none-tablet acceptBet parent row h-around">
      <!-- begin matchStatus__bet -->
      <div 
        id="acceptBetLabel" 
        class="matchStatus__bet">
        Ставка
      </div>
      <!-- end matchStatus__bet -->

      <!-- begin matchStatus__ratio -->
      <div 
        id="acceptRatioLabel" 
        class="matchStatus__ratio">
        Коэф-т.
      </div>
      <!-- end matchStatus__ratio -->
  
      <!-- begin matchStatus__win -->
      <div class="matchStatus__win">
        Выигрыш
      </div>
    </div>
    <!-- end acceptBet -->

    <!-- begin matchStatusButtons -->
    <!-- Плейсхолдер для ровной сетки -->
    <div class="matchStatusButtons"/>
    <!-- end matchStatusButtons -->
  </div>
  <!-- end userBets -->
</template>

<script>
export default {
  name: "BetsMatchPopupBetsHeader"
};
</script>

<style lang="sass" scoped>
@import "../assets/sass/conf/_colors.sass"
@import "../assets/sass/conf/_sizes.sass"
@import "../assets/sass/conf/_breakpoints.sass"
@import "../assets/sass/conf/_easing.sass"

.offerBet     
 	@include breakpoint($tablet-less)
      	min-width: em(220, 10)
</style>
