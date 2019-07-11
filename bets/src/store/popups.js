import popupsNames from "@/constants/popups";
import { requestUrls } from "@/constants/conf";

// Vuex Documentation: https://vuex.vuejs.org/ru/modules.html

const popups = {
  namespaced: true,
  state: {
    [popupsNames.authorization]: false,
    [popupsNames.personalRoom]: false,
    [popupsNames.match]: false,
    [popupsNames.newThread]: false,
    [popupsNames.notification]: false,
    updateDataState: {
      [popupsNames.match]: false
    },
    testData: {}
  },
  getters: {
    isMatchDataUpdated(state) {
      return state.updateDataState[popupsNames.match];
    }
  },
  mutations: {
    updateUpdatingDataState(state, { isUpdated, popupName }) {
      state.updateDataState[popupName] = isUpdated;
    },

    switchPopupState(state, { isOpened, popupName }) {
      state[popupName] = isOpened;
    }
  },
  actions: {
    closeNotificationPopup({ commit }) {
      commit("switchPopupState", {
        isOpened: false,
        popupName: popupsNames.notification
      });

      commit("authorization/cleanRequestState", null, { root: true });
    },

    openNotificationPopupWithMessage(
      { commit, dispatch },
      { isError, message, isAuthorizationBlock }
    ) {
      commit("switchPopupState", {
        isOpened: true,
        popupName: popupsNames.notification
      });

      dispatch(
        "authorization/showResponseMessage",
        {
          isError,
          message,
          isAuthorizationBlock
        },
        { root: true }
      );
    },

    openMatch({ commit }, matchUrl) {
      commit("switchPopupState", {
        isOpened: true,
        popupName: popupsNames.match
      });

      commit("bets/setChoosenMatchUrl", matchUrl, { root: true });
    },

    closeMatchPopupAndCleanState({ commit }) {
      commit("switchPopupState", {
        isOpened: false,
        popupName: popupsNames.match
      });

      commit("updateUpdatingDataState", {
        isUpdated: false,
        popupName: popupsNames.match
      });

      commit("bets/setChoosenMatchUrl", "", { root: true });
    },

    openPopupAndLoadMatch({ commit }, matchUrl) {
      commit("switchPopupState", true);
      commit("setChoosenMatchUrl", matchUrl);
    },

    openAuthPopupAndShowForm({ commit }, formName) {
      commit("switchPopupState", {
        popupName: popupsNames.authorization,
        isOpened: true
      });

      commit("authorization/switchForm", formName, { root: true });
    }
  }
};

export default popups;
