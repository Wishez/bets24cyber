<template>
  <!-- begin userTransactionsHead -->
  <div 
    id="userTransactionsHead" 
    class="tableHead transactionsGrid 
          userTransactionsHead
          font-size_12 font-weight_semibold h-centered text_centered
           padding_horizontal-tablet">
    <!-- begin transactionsGridDate -->
    <div 
      scope="col" 
      class="transactionsGridDate">
      Дата
    </div>
    <!-- end transactionsGridDate -->

    <!-- begin transactionsGridTransactions -->
    <div 
      scope="col" 
      class="transactionsGridAction">
      Транзакция
    </div>
    <!-- end transactionsGridTransactions -->

    <!-- end transactionsGridStatus -->
    <div 
      scope="col" 
      class="transactionsGridStatus">
      Статус
    </div>
    <!-- end transactionsGridStatus -->

    <!-- begin transactionsGridSum -->
    <div 
      scope="col" 
      class="transactionsGridSum text-indent_10 text-indent_zero-tablet">
      Сумма
    </div>
    <!-- end transactionsGridSum -->

    <!-- begin transactionsGridCommission -->
    <div 
      scope="col" 
      class="transactionsGridCommission text-indent_10 text-indent_zero-tablet">
      Комиссия
    </div>
    <!-- end transactionsGridCommission -->

    <!-- begin transactionsGridRest -->
    <div 
      scope="col" 
      class="transactionsGridRest">
      Остаток
    </div>
    <!-- end transactionsGridRest -->
  </div>
  <!-- end  userTransactionsHead -->
</template>

<script>
export default {
  name: "UserTransactionsHeader"
};
</script>

<style lang="sass" scoped>
	
</style>
