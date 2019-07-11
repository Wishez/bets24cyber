import { mutations } from "@/store";

const { changeTimezone } = mutations;

describe("Index VUEX store.", () => {
  it("Change timezone.", () => {
    const currentTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const state = {
      timezone: currentTimezone
    };
    const timezone = "Asia/Magadan";

    changeTimezone(state, timezone);

    expect(state.timezone, timezone);
  });
});
