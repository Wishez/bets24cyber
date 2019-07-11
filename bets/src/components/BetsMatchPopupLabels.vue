<template>
  <!-- begin popupMatchTitles -->
  <div class="popupMatchTitles text_upper matchBetGrid text_right">
    <!-- begin matchStatusResults -->
    <div class="matchStatusResults"/>
    <!-- end matchStatusResults -->
    <p 
      class="offerBet font-weight_semibold text_upper font-size_12 margin-top_zero parent centered alignParagraph"
    >
      Предложить ставку
    </p>
    <p 
      class="acceptBet font-weight_semibold text_upper font-size_12 margin-top_zero parent centered alignParagraph"
    >
      Принять ставку
    </p>
    <div class="matchStatusButtons"/>
  </div>
  <!-- end popupMatchTitles -->
</template>

<script>
export default {
  name: "BetsMatchPopupLabels"
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
      
.popupMatchTitles
  	margin-bottom: em(11)
  	@include breakpoint($tablet-less)
    	display: none


  
.alignParagraph
  	@include breakpoint($tablet)
     	margin-left: em(-8, 12)
  	@include breakpoint($tablet-less)
    	justify-content: center
  	@include breakpoint($phone-less)
      	min-width: 0
  	letter-spacing: .05em;
</style>
