import Vue from "vue";
import Vuex from "vuex";
import bets from "./bets";
import personalRoom from "./personalRoom";
import matches from "./matches";
import popups from "./popups";
import pagination from "./pagination";
import authorization from "./authorization";
import popupsNames from "@/constants/popups";
import sorters from "./sorters";

Vue.use(Vuex);

export const mutations = {
  changeTimezone(state, timezone) {
    state.timezone = timezone;
  }
};

export const actions = {
  changeTimezone({ commit }, timezone) {
    commit("changeTimezone", timezone);
    localStorage.userChoosenTimezone = timezone;
  },

  openNotificationPopup({ commit }) {
    commit(
      "popups/switchPopupState",
      {
        isOpened: true,
        popupName: popupsNames.noftification
      },
      {
        root: false
      }
    );
  }
};

export default new Vuex.Store({
  modules: {
    bets,
    personalRoom,
    matches,
    popups,
    pagination,
    authorization,
    sorters
  },
  state: {
    csrftoken: Cookies.get("csrftoken"),
    // https://stackoverflow.com/questions/1091372/getting-the-clients-timezone-in-javascript
    timezone:
      localStorage.userChoosenTimezone ||
      Intl.DateTimeFormat().resolvedOptions().timeZone,
    document: document
  },
  mutations,
  actions
});
