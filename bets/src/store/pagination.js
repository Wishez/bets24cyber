import { timeout, slideTo } from "@/constants/pureFunctions";

const paginations = {
  namespaced: true,
  state: {
    transactions: {
      dataPerPage: 13,
      currentPage: +localStorage.lastUserTransactionsPage || 1,
      quantityPages: 1
    },
    acceptedBets: {
      dataPerPage: 11,
      currentPage: +localStorage.lastUserCreatedBetsPage || 1,
      quantityPages: 1
    },
    createdBets: {
      dataPerPage: 11,
      currentPage: +localStorage.lastUserAcceptedBetsPage || 1,
      quantityPages: 1
    },
    contactCenter: {
      dataPerPage: 12,
      currentPage: +localStorage.lastUserContactCenterPage || 1,
      quantityPages: 1
    },
    choosenThread: {
      dataPerPage: 5,
      currentPage: +localStorage.lastChoosenThreadPage || 1,
      quantityPages: 1
    }
  },
  mutations: {
    changePaginationPage(
      state,
      { choosenPage, dataName, selectorToSlide = ".personalRoom section" }
    ) {
      if (selectorToSlide) {
        timeout(() => {
          slideTo({
            selector: selectorToSlide
          });
        }, 250);
      }

      const moduleName = dataName[0].toLowerCase() + dataName.slice(1);

      state[moduleName].currentPage = +choosenPage;
      localStorage[`lastUser${dataName}Page`] = +choosenPage;
    }
  },
  actions: {
    switchContactCenterPageAndCloseThreadIfNeeded(
      { commit, state, rootState },
      { choosenPage, dataName }
    ) {
      // Смена страницы в контакт центре.
      timeout(() => {
        commit("changePaginationPage", {
          choosenPage,
          dataName
        });
      }, 250);

      // Litch для закрытие дискусса.
      if (rootState.personalRoom.user.choosenThread.isOpened) {
        commit("personalRoom/changeDisplayThreadState", false, { root: true });
      }
    }
  }
};

export default paginations;
