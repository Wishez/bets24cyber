import formsNames from "@/constants/authorization";
import popupsNames from "@/constants/popups";
import { timeout } from "@/constants/pureFunctions";

const authorization = {
  namespaced: true,
  state: {
    requestStatus: {
      isRequesting: false,
      message: "",
      isSuccess: false,
      isError: false
    },

    [formsNames.recoverPassword]: {
      email: "",
      isShown: false
    },
    [formsNames.registration]: {
      isShown: false,
      username: "",
      email: "",
      password: "",
      repeatedPassword: ""
    },
    [formsNames.login]: {
      isShown: false,
      username: "",
      password: ""
    },
    [formsNames.notification]: {
      isShown: false
    }
  },
  mutations: {
    switchRequestingState(state, isRequesting) {
      state.requestStatus.isRequesting = isRequesting;
    },

    switchForm(state, formName) {
      for (let key in formsNames) {
        const form = state[key];

        if (key === formName) {
          timeout(() => {
            form.isShown = true;
          }, 250);
        } else {
          form.isShown = false;
        }
      }
    },

    cleanRequestState(state) {
      const defaultRequestStatus = {
        isError: false,
        isSuccess: false,
        isRequesting: false,
        message: ""
      };

      state.requestStatus = {
        ...defaultRequestStatus
      };
    },

    cacheUserData(state, userData) {
      return localforage.setItem("userAuthorizationData", userData);
    }
  },
  actions: {
    logOutAndCleanCache({ commit }) {
      const defaultUserData = {
        name: "",
        id: null,
        avatar: "None",
        balance: null,
        isLogged: false
      };

      commit(
        "popups/switchPopupState",
        {
          popupName: popupsNames.personalRoom,
          isOpened: false
        },
        { root: true }
      );

      timeout(() => {
        commit("personalRoom/setUserPersonalData", defaultUserData, {
          root: true
        });
        commit("cacheUserData", null);
      }, 250);
    },
    notifyAboutSuccesRegistration(
      { commit, dispatch },
      { formName, userData, isError, errorMessage, successMessage }
    ) {
      const balance = userData.balance;
      const newUserData = {
        id: userData.id,
        name: userData.name,
        balance: balance ? balance : 0,
        avatar: userData.avatar
      };
      const responseNotificationOptions = {
        message: isError ? errorMessage : successMessage,
        isError,
        formName
      };

      if (!isError) {
        commit("personalRoom/setUserPersonalData", newUserData, {
          root: true
        });
      }

      dispatch("showResponseMessage", responseNotificationOptions);

      commit("cacheUserData", {
        ...userData,
        password: userData.password
      });
    },
    showUserDataIfUserLogged(
      { commit, dispatch },
      { userData, formName, isError, errorMessage }
    ) {
      commit("switchRequestingState", false);

      if (isError) {
        dispatch("showResponseMessage", {
          message: errorMessage,
          isError
        });
      } else {
        timeout(() => {
          commit(
            "popups/switchPopupState",
            {
              isOpened: false,
              popupName: popupsNames.authorization
            },
            { root: true }
          );
        }, 250);

        commit("personalRoom/setUserPersonalData", userData, {
          root: true
        });

        commit("cacheUserData", userData);
      }
    },

    switchForm({ commit }, formName) {
      commit("cleanRequestState");
      commit("switchForm", formName);
    },

    showResponseMessage(
      { commit, state },
      {
        isError,
        message,
        isAuthorizationBlock = false,
        isActionsBlock = false,
        isConfirmBlock = false
      }
    ) {
      const updatedRequestStatus = {
        isSuccess: !isError,
        message,
        isError,
        isAuthorizationBlock,
        isActionsBlock,
        isConfirmBlock
      };

      state.requestStatus = {
        ...updatedRequestStatus
      };

      commit("switchRequestingState", false);
    }
  }
};

export default authorization;
