import { timeout } from "@/constants/pureFunctions";

export function showChangingRatio() {
  if (this.lastRatioValues.left !== -1) {
    this.$nextTick(() => {
      styleRatio.call(this);

      timeout(() => {
        unstyleRatio.call(this);
      }, 3000);
    });
  }
}

export function styleRatio() {
  this.isRightRatioArrowUp = lastValueMoreOf(
    this.rightTeamRatio,
    this.lastRatioValues.right
  );
  this.isLeftRatioArrowUp = lastValueMoreOf(
    this.leftTeamRatio,
    this.lastRatioValues.left
  );

  timeout(() => {
    this.isArrowsShown = true;
  }, 250);
}

export function unstyleRatio() {
  this.isArrowsShown = false;
  this.isRightRatioArrowUp = false;
  this.isLeftRatioArrowUp = false;
}

export function lastValueMoreOf(currentValue, lastValue) {
  const isNotEqual = lastValue !== currentValue;
  const isCurrentValueUpper = lastValue < currentValue;

  return isNotEqual && isCurrentValueUpper;
}
