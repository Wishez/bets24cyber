<template>
  <!-- begin tableHead -->
  <div 
    role="rowgroup"
    class="tableHead userMessagesGrid 
            display_grid userMessagesGrid_column font-size_12 someBetContainer_tablet font-weight_semibold padding-bottom_base-tablet padding-top_small-tablet background-color_white">
    <!-- begin userMessagesGridId -->
    <span 
      role="columnheader" 
      class="userMessagesGridId">
      ID
    </span>
    <!-- end userMessagesGridId -->

    <!-- begin userMessagesGridTitle -->
    <span 
      role="columnheader" 
      class="userMessagesGridTitle text_centered">
      Заголовок
    </span>
    <!-- end userMessagesGridTitle -->

    <!-- begin userMessagesGridLastAnswerDate -->
    <span 
      role="columnheader" 
      class="userMessagesGridLastAnswerDate">
      Последний ответ
    </span>
    <!-- end userMessagesGridLastAnswerDate -->

   
 
    <!-- begin userMessagesGridStatus -->
    <span
      role="columnheader"
      class="userMessagesGridStatus text_centered">
      Статус
    </span>
    <!-- end userMessagesGridStatus -->

    <!-- begin userMessagesGridActions -->
    <span 
      role="columnheader"
      class="userMessagesGridActions parent">
      Действия
    </span>
    <!-- end userMessagesGridActions -->              
  </div>
  <!-- end tableHead -->
</template>

<script>
export default {
  name: "ContactCenterTableHead"
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'
@import '../../assets/sass/conf/_easing.sass'



.userMessagesGrid

  &Title
    @include breakpoint($tablet)
      text-indent: -30px
    @include breakpoint($tablet-less)
      // text-align: ce
      text-align: left

    @include breakpoint($phone-less)

  &LastAnswerDate
    @include breakpoint($tablet)
      text-indent: 2.5px

  &Status

    @include breakpoint($tablet-less)
      text-align: left
      
    @include breakpoint($phone-less)
      text-align: right

  &Actions

    @include breakpoint($tablet)

      text-indent: 15px
</style>
