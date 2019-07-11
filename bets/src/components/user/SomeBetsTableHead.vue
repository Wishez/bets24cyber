<template>
  <!-- begin tableHead -->
  <div 
    role="rowgroup"
    class="tableHead someBetsGrid 
            display_grid someBetsGrid_column font-size_12 someBetContainer_tablet font-weight_semibold">
    <!-- begin someBetsGridId -->
    <span 
      role="columnheader" 
      class="someBetsGridId">
      ID
    </span>
    <!-- end someBetsGridId -->

    <!-- begin someBetsGridId -->
    <span 
      role="columnheader" 
      class="someBetsGridGameLogo">
      Игра
    </span>
    <!-- end someBetsGridId -->

    <!-- begin someBetsGridId -->
    <span 
      role="columnheader" 
      class="someBetsGridDate text-indent_10 text-indent_zero-bet-tablet text_centered-bet-tablet">
      Дата
    </span>
    <!-- end someBetsGridId -->


    <!-- begin someBetsGridMatch -->
    <span 
      role="columnheader"
      class="someBetsGridMatch text-indent_10 text-indent_zero-bet-tablet">
      Матч
    </span>
    <!-- end someBetsGridMatch -->

    <!-- begin someBetsGridBet -->
    <div 
      role="columnheader"
      class="someBetsGridBet">
      Ставка
    </div>
    <!-- end someBetsGridBet -->

    <!-- begin someBetsGridStatus -->
    <span
      role="columnheader"
      class="someBetsGridStatus text-indent_10 text-indent_zero-bet-tablet text_centered-bet-tablet">
      Статус
    </span>
    <!-- end someBetsGridStatus -->

    <!-- begin someBetsGridActions -->
    <span 
      role="columnheader"
      class="someBetsGridActions text-indent_10 parent">
      Действия
    </span>
    <!-- end someBetsGridActions -->              
  </div>
  <!-- end tableHead -->
</template>

<script>
export default {
  name: "SomeBetsTableHead"
};
</script>

<style lang="sass">
	
</style>
