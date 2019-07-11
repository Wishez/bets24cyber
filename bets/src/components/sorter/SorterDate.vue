<template>
  <div
    :title="getDateRangeTitle()"
    :class="{
      'timeRangeSorter parent row h-around wrap v-centered height_fill relative' : true,
      [`timeRangeSorter_${modifier}`]: modifier,
      [className]: className,
      [`sorterSection sorterSection_styled`]: styled
  }">

    <span :id="'dataLabel_' + sorterDateId">Дата:</span>
    <datepicker
      v-model="sinceDate"
      :wrapper-class="{
        'dateInputContainer': true
      }"
      :input-class="{
        'dateInputContainer__input croped_line': true
      }"
      :title="getHumanReadableDate(sinceDate)"
      :name="sinceDateName"
      calendar-class="position_centered-phone"
      aria-labeledby="dateLabel"
      language="ru"
      placeholder="с"
      clear-button
      @selected="onSelect('set-since-date', 'sinceDate')"
    />

    <chevron-icon
      v-if="!sinceDate"
      position="down"
      class="absolute font-size_10 datePickerChevron datePickerChevron_since"/>

    <datepicker
      v-model="untilDate"
      :wrapper-class="{
        'dateInputContainer': true
      }"
      :input-class="{
        'dateInputContainer__input croped_line': true
      }"
      :title="getHumanReadableDate(untilDate)"
      :name="untilDateName"
      calendar-class="position_right"
      aria-labeledby="dateLabel"
      language="ru"
      placeholder="до"
      clear-button
      @selected="onSelect('set-until-date', 'untilDate')" />

    <chevron-icon
      v-if="!untilDate"
      position="down"
      class="absolute
            font-size_10 datePickerChevron datePickerChevron_until"/>

  </div>
  <!-- end timeRageSorter -->

</template>

<script>
import { ID, transformDate } from "@/constants/pureFunctions";
import Datepicker from "vuejs-datepicker";

export default {
  name: "SorterDate",
  components: {
    Datepicker
  },
  props: {
    className: {
      type: String,
      required: false,
      default: ""
    },
    modifier: {
      type: String,
      required: false,
      default: ""
    },
    styled: {
      type: Boolean,
      required: false,
      default: false
    },
    sinceDateName: {
      type: String,
      required: true
    },
    untilDateName: {
      type: String,
      required: true
    },
    defaultDates: {
      type: Object,
      required: false,
      default: () => ({
        until: "",
        since: ""
      })
    }
  },
  data: () => ({
    untilDate: "",
    sinceDate: ""
  }),
  computed: {
    sorterDateId() {
      return ID();
    }
  },
  mounted() {
    this.$nextTick(() => {
      this.untilDate = this.defaultDates.until;
      this.sinceDate = this.defaultDates.since;
    });
  },
  methods: {
    onSelect(event, key) {
      this.$nextTick(function() {
        const selectedDate = this[key];

        this.$emit(event, transformDate(selectedDate, "MM.DD.YYYY hh:mm"));
      });
    },

    getHumanReadableDate(date) {
      // Без возврата неправильного формата даты.
      return date && transformDate(date, "MM.D.YYYY");
    },
    // Информирование при наведение о диапозоне сортировки по дате.
    getDateRangeTitle() {
      let title = "";

      if (this.sinceDate) {
        title += `С ${this.getHumanReadableDate(this.sinceDate)}; `;
      }

      if (this.untilDate) {
        title += `По ${this.getHumanReadableDate(this.untilDate)}; `;
      }

      return title;
    }
  }
};
</script>

<style lang="sass" scoped>
@import '../../assets/sass/conf/_colors.sass'
@import '../../assets/sass/conf/_easing.sass'
@import '../../assets/sass/conf/_sizes.sass'
@import '../../assets/sass/conf/_breakpoints.sass'

.timeRangeSorter
  margin-left: em(12, 12)
  padding-right: .15rem

  @include breakpoint($betsTabletLess)
    padding-left:   1em
  @include breakpoint($tablet-less)
    margin-left: 0




.dateInputContainer
  display: inline-block

  @include breakpoint($tablet)
    max-width: 35%
  @include breakpoint($desktop)
    max-width: em(50, 12)
  @include breakpoint($tablet-less)
    max-width: 40%

.datePickerChevron
  &_until
    right: em(20, 10)
  &_since
</style>
