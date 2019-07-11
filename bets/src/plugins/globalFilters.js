import Vue from "vue";
import moment from "moment-timezone";

Vue.filter(
  "fixSum",
  (sum, numbersAfterDot = 2) =>
    typeof sum === "string" ? sum : +sum.toFixed(numbersAfterDot)
);

Vue.filter("formatDate", (date, format = "L") => {
  if (typeof date === "string") {
    date = date.replace(/[-\.]/g, "/");
  }

  const tz = $storage.get("choosenTimezone");

  return moment(date)
    .tz(tz)
    .format(format);
});
