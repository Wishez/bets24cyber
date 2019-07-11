<template>
  <!-- begin tableTransactionsRow transactionsGrid -->
  <div class="tableTransactionsRow bordered-bottom bordered-bottom_gray height_fill transactionsGrid font-size_12 centered padding_horizontal-tablet padding-bottom_small-tablet">
    <!-- begin transactionsGridDate -->
    <time 
      :datetime="transactionDate | formatDate('L HH:mm')" 
      class="transactionsGridDate color_paleBlue"
    >
      {{ transactionDate | formatDate('DD MMMM YYYY, HH:mm') }}
    </time>
    <!-- end transactionsGridDate -->

    <!-- begin transactionsGridTransactions -->
    <p class="transactionsGridAction margin-top_zero">
      {{ userAction }}
    </p>
    <!-- end transactionsGridTransactions -->

    <!-- end transactionsGridStatus -->
    <div 
      :class="{
        'transactionsGridStatus': true,
        color_green: !transactionStatus,
        color_paleBlue: transactionStatus
    }">
      {{ transactionStatus ? 'Ожидается' : 'Выполнено' }}
    </div>
    <!-- end transactionsGridStatus -->

    <!-- begin transactionsGridSum -->
    <div 
      class="transactionsGridSum padding-left_large-rem 
    padding-left_zero-tablet font-weight_semibold">
      $ {{ sum | fixSum }}
    </div>
    <!-- end transactionsGridSum -->

    <!-- begin transactionsGridCommission -->
    <div class="transactionsGridCommission font-weight_semibold">
      $ {{ commission | fixSum }}
    </div>
    <!-- end transactionsGridCommission -->

    <!-- begin transactionsGridRest -->
    <div 
      class="transactionsGridRest padding-left_large-rem 
    padding-left_zero-tablet font-weight_semibold">
      $ {{ rest | fixSum }}
    </div>
    <!-- end transactionsGridRest -->
  </div>
  <!-- end header transactionsGrid -->
</template>

<script>
export default {
  name: "UserTransaction",
  props: {
    rest: {
      type: Number,
      required: true
    },
    sum: {
      type: Number,
      required: true
    },
    commission: {
      type: Number,
      required: true
    },
    transactionStatus: {
      type: Boolean,
      required: true
    },
    transactionDate: {
      type: [Date, String],
      required: true
    },
    userAction: {
      type: String,
      required: true
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'

.tableTransactionsRow
	line-height: 1.3

.transactionsGrid
	&Status
		text-indent: 40px
		
		@include breakpoint($tablet-less)
			text-indent: 0

	&Commission
		padding-left: 2.5rem

		@include breakpoint($tablet-less)
			padding-right: 0
		
</style>
