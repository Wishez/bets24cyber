export const state = {
  betsSorter: {
    status: "all",
    search: "",
    sinceDate: "",
    untilDate: "",
    type: ""
  },
  transactions: {},
  createdBets: {},
  acceptedBets: {},
  contactCenter: {}
};

export const mutations = {
  setSorterSettings(state, { name, sorterSettings }) {
    state[name] = sorterSettings;
  },

  setBetsType(state, choosenType) {
    state.betsSorter.type = choosenType;
  }
};

export const actions = {};

export default {
  namespaced: true,
  state,
  mutations
};
