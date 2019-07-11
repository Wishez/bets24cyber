import { timeout } from "@/constants/pureFunctions";

export default {
  namespaced: true,
  state: {
    activeGameId: "dota",
    currentSorterType: "Турнир",
    isSorterTypesShown: false,
    choosenMatchUrl: false,
    bets: [],
    bet: {},
    sorterTypes: [
      {
        name: "Турнир"
      },
      {
        name: "Левая команда"
      },
      {
        name: "Правая команда"
      }
    ]
  },
  getters: {
    isEmptyMatchPopup(state) {
      return !!Object.keys(state.bet).length;
    }
  },
  mutations: {
    cleanBet(state) {
      state.bet = {};
    },
    updateMatches(state, newMatches) {
      state.bets = newMatches;
    },
    switchGameId(state, activeGameId) {
      state.activeGameId = activeGameId;
    },

    updateChoosenBet(state, { leftTeam, channels, rightTeam, league, match }) {
      state.bet = {
        leftTeam,
        rightTeam,
        league,
        match,
        channels
      };
    },

    setChoosenMatchUrl(state, url) {
      state.choosenMatchUrl = url;
    },
    switchSorterTypesState(state) {
      state.isSorterTypesShown = !state.isSorterTypesShown;
    },
    setChoosenSorterType(state, choosenSorterType) {
      state.currentSorterType = choosenSorterType;
    }
  },
  actions: {
    choiceSorterTypeAndCloseList({ commit }, choosenSorterType) {
      commit("setChoosenSorterType", choosenSorterType);
      commit("switchSorterTypesState");
    }
  }
};
