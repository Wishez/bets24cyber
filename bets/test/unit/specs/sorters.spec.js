import { mutations } from "@/store/sorters";

const { setBetsType } = mutations;

describe("Sorter VUEX module.", () => {
  it("Set bets' sorter type.", () => {
    const state = {
      betsSorter: {
        type: ""
      }
    };
    const type = "TDD";

    setBetsType(state, type);

    expect(state.betsSorter.type, type);
  });
});
