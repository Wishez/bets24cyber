import {
  wm,
  paypal,
  mastercard,
  bitcoin,
  eth,
  visa,
  wallet,
  acceptedBets,
  createdBets,
  envelope
} from "@/assets/images/personalRoom";

import { timeout } from "@/constants/pureFunctions";

import {
  viewNames,
  sorterTypesNames,
  roomSectionsHeight,
  generateSomeBetsSorterOptions,
  userTestDialogs,
  generateUserData,
  asSorter
} from "@/constants/personalRoom";
import popupsNames from "@/constants/popups";

export const mutations = {
  setUserBalance(state, balance) {
    state.user.balance = balance;
  },

  setNewUserBet(state, newUserBet) {
    state.user.newUserBet = newUserBet;
  },

  cleanNewUserBet(state) {
    state.user.newAcceptedBet = false;
  },

  deleteUserBet(state, { id, moduleName }) {
    const someBets = state.user[moduleName];
    let index;

    someBets.forEach(getBetIndex);

    function getBetIndex(bet, betIndex) {
      if (void 0 === index && bet.bet.id === id) {
        index = betIndex;
      }
    }

    if (void 0 !== index) {
      state.user[moduleName] = [
        ...someBets.slice(0, index),
        ...someBets.slice(index + 1)
      ];
    }
  },

  setUserPersonalData(state, { id, name, balance, avatar, isLogged = true }) {
    const userNewPersonalData = {
      id,
      name,
      balance,
      avatar,
      isLogged
    };
    const userOldPersonalData = state.user;

    state.user = {
      ...userOldPersonalData,
      ...userNewPersonalData
    };
  },

  setAvailabledPaymentsForUser(state, availabledPayments) {
    state.payments.availabled = availabledPayments;
  },

  showBetStatistic(state, { newStatistic, statisticType }) {
    state.user.statistic[statisticType] = newStatistic;
  },

  hideBetStatistic(state, statisticType) {
    state.user.statistic[statisticType] = {};
  },

  // Асинхронно загружет тестовые данные в хранилище модуля.
  setUserData(state, { moduleName, newUserData = false }) {
    const userData = state.user[moduleName];

    if (!userData.length && !newUserData) {
      generateUserData(moduleName).then(generatedData => {
        state.user[moduleName] = generatedData;
      });
    }

    if (newUserData) {
      timeout(() => {
        state.user[moduleName] = newUserData;
      }, 250);
    }
  },

  cleanUserData(state, moduleName) {
    const userData = state.user[moduleName];

    if (userData.length) {
      state.user[moduleName] = [];
    }
  },

  switchPersonalRoomView(state, viewName) {
    state.currentOpenedView = viewName;
    localStorage.currentOpenedView = viewName;
  },

  changeCurrentPaymentAction(state, actionName) {
    state.currentPaymentAction = actionName;
    localStorage.currentPaymentAction = actionName;
  },

  switchListSorterTypeState(state, sorterTypesName) {
    // Закрытие последнего всплывающего списка
    // с сортировкой.
    const lastOpenedList = state.lastOpenedList;

    if (lastOpenedList && sorterTypesName !== lastOpenedList) {
      state[state.lastOpenedList].isListOpened = false;
      state.lastOpenedList = "";
    }

    state[sorterTypesName].isListOpened = !state[sorterTypesName].isListOpened;
    state.lastOpenedList = sorterTypesName;
  },
  changeCurrentSorterType(state, { sorterTypesName, choosenSorterType }) {
    state[sorterTypesName].currentSorterType = choosenSorterType;

    return Promise.resolve().then(function() {
      timeout(() => {
        state[state.lastOpenedList].isListOpened = false;
        state.lastOpenedList = "";
        localStorage[sorterTypesName] = JSON.stringify(choosenSorterType);
      }, 100);
    });
  },

  changeDisplayThreadState(state, opened = false) {
    state.user.choosenThread.isOpened = opened;

    // Очищается состояние.
    if (!opened) {
      state.user.choosenThread.data = null;
      state.user.choosenThread.hash = false;
    }
  },

  getThreadData(state, { threadId, hash, threadData }) {
    const choosenThreadData = {
      ...threadData,
      ...userTestDialogs[threadId]
    };

    state.user.choosenThread.data = choosenThreadData;
    state.user.choosenThread.hash = hash;
  },

  // Dialog actions.
  updateThreadDialog(state, { message, images, date, threadId }) {
    state.user.choosenThread.data.dialog.push({
      isUser: true,
      images,
      message,
      date
    });

    timeout(() => {
      // Смена состояния диалога.
      const choosenThread = state.user.choosenThread;

      state.user.contactCenter = state.user.contactCenter.reduce(
        (contactCenterData, thread) => {
          if (threadId === thread.threadId) {
            thread.statusName = choosenThread.data.companionName;
          }

          return [...contactCenterData, thread];
        },
        []
      );
    }, 250);
  }
};

export const actions = {
  showUserPaymentsByChoosenAction(
    { commit },
    { userAction, availabledPayments }
  ) {
    commit("changeCurrentPaymentAction", userAction);

    commit("setAvailabledPaymentsForUser", availabledPayments);
  },

  closeThreadAndCleanPagination({ commit }) {
    commit("changeDisplayThreadState", false);
    commit(
      "pagination/changePaginationPage",
      {
        dataName: "ChoosenThread",
        choosenPage: 1
      },
      {
        root: true
      }
    );
  },

  updateDialogAndSwitchPageIfNeeded(
    { commit },
    { message, images, date, threadId, lastChoosenThreadPage }
  ) {
    commit("updateThreadDialog", {
      message,
      images,
      date,
      threadId
    });

    commit(
      "pagination/changePaginationPage",
      {
        dataName: "ChoosenThread",
        choosenPage: lastChoosenThreadPage
      },
      {
        root: true
      }
    );
  },

  openChoosenThread({ commit }, { threadId, hash, threadData }) {
    commit("getThreadData", {
      threadId,
      hash,
      threadData
    });

    commit("changeDisplayThreadState", true);
  },

  openView({ commit }, choosenView) {
    commit("switchPersonalRoomView", choosenView);
  },

  makeNewThreadAndOpenThread(
    { commit, dispatch, state },
    { threadId, threadData, message }
  ) {
    const timestamp = threadData.lastAnswerDate;
    const user = state.user;

    user.contactCenter.push(threadData);

    userTestDialogs[threadId] = {
      id: threadId,
      companionName: "",
      dialog: [
        {
          isUser: true,
          date: timestamp,
          images: [],
          message
        }
      ]
    };

    // Second
    timeout(() => {
      // Fourth
      timeout(() => {
        dispatch("openChoosenThread", {
          hash: threadData.hash,
          threadId,
          threadData: {
            lastAnswerDate: timestamp,
            title: threadData.title,
            status: {
              name: threadData.status.name,
              message: threadData.status.message
            },
            threadId
          }
        });

        user.newThread.message = "";
        user.newThread.title = "";
      }, 500);

      // Third
      commit(
        "pagination/changePaginationPage",
        {
          choosenPage: 1,
          dataName: "ContactCenter"
        },
        {
          root: true
        }
      );
    }, 150);

    // First
    commit(
      "popups/switchPopupState",
      {
        isOpened: false,
        popupName: popupsNames.newThread
      },
      {
        root: true
      }
    );
  }
};

export const state = {
  roomHeight: roomSectionsHeight[viewNames.balance],
  currentOpenedView: localStorage.currentOpenedView || viewNames.balance,
  currentPaymentAction: "",
  user: {
    avatar: null,
    name: null,
    balance: null,
    id: null,
    isLogged: false,
    transactions: [],
    statistic: {
      created: {},
      accepted: {}
    },
    createdBets: [],
    acceptedBets: [],
    contactCenter: [],
    choosenAcceptedBet: false,
    choosenCreatedBet: false,

    choosenThread: {
      isOpened: false,
      data: null,
      hash: false
    },
    newThread: {
      message: "",
      title: ""
    },
    newUserBet: false
  },
  links: [
    {
      label: "Баланс",
      viewName: viewNames.balance,
      icon: wallet
    },
    {
      label: "Созданные ставки",
      viewName: viewNames.createdBets,
      icon: createdBets
    },
    {
      label: "Принятые ставки",
      viewName: viewNames.acceptedBets,
      icon: acceptedBets
    },
    {
      label: "Контакт центр",
      viewName: viewNames.contactCenter,
      icon: envelope
    }
  ],
  payments: {
    ways: [
      {
        label: "Visa",
        link: "#",
        icon: visa,
        id: "visa"
      },
      {
        label: "Master Card",
        link: "#",
        icon: mastercard,
        id: "mastercard"
      },
      {
        label: "Web Money",
        link: "#",
        icon: wm,
        id: "webmoney"
      },
      {
        label: "PayPal",
        link: "#",
        icon: paypal,
        id: "paypal"
      },
      {
        label: "Bitcoin",
        link: "#",
        icon: bitcoin,
        id: "bitcoin"
      },
      {
        label: "Ether",
        link: "#",
        icon: eth,
        id: "ether"
      }
    ],
    availabled: []
  },
  [sorterTypesNames.transactions.bets]: asSorter({
    types: [],
    currentSorterType: getLocalItem(sorterTypesNames.transactions.bets) || {
      id: 0,
      name: "Ставка"
    }
  }),
  [sorterTypesNames.transactions.type]: asSorter({
    types: [],
    currentSorterType: getLocalItem(sorterTypesNames.transactions.type) || {
      id: 0,
      name: "Тип"
    }
  }),

  [sorterTypesNames.transactions.status]: asSorter({
    types: [],
    currentSorterType: getLocalItem(sorterTypesNames.transactions.status) || {
      id: 0,
      name: "Статус"
    }
  }),
  [sorterTypesNames.createdBets.game]: generateSomeBetsSorterOptions(
    sorterTypesNames.createdBets.game,
    "games"
  ),
  [sorterTypesNames.createdBets.status]: generateSomeBetsSorterOptions(
    sorterTypesNames.createdBets.status,
    "statuses"
  ),

  [sorterTypesNames.acceptedBets.game]: generateSomeBetsSorterOptions(
    sorterTypesNames.acceptedBets.game,
    "games"
  ),
  [sorterTypesNames.acceptedBets.status]: generateSomeBetsSorterOptions(
    sorterTypesNames.acceptedBets.status,
    "statuses"
  ),
  [sorterTypesNames.contactCenter.status]: asSorter({
    types: [],
    currentSorterType: getLocalItem(sorterTypesNames.contactCenter.status) || {
      id: 0,
      name: "Статус"
    }
  }),

  lastOpenedList: ""
};

export const getters = {
  createdBetsByTimeDecreasing(state) {
    const sortedCreatedBets = getSortedData({
      data: state.user.createdBets,
      compareFunction: sortBetsByDecreasingTime
    });

    return sortedCreatedBets;
  },

  acceptedBetsByTimeDecreasing(state) {
    const sortedAcceptedBets = getSortedData({
      data: state.user.acceptedBets,
      compareFunction: sortBetsByDecreasingTime
    });

    return sortedAcceptedBets;
  },

  transactionsByTimeDecreasing(state) {
    const sortedTransactions = getSortedData({
      data: state.user.transactions,
      compareFunction: sortTransactionsByDecreasingTime
    });

    return sortedTransactions;
  },

  messagesByTimeDecreasing(state) {
    const sortedMessages = getSortedData({
      data: state.user.contactCenter,
      compareFunction: sortMessagesByDecreasingTime
    });

    return sortedMessages;
  }
};

export default {
  namespaced: true,
  state,

  getters,

  mutations,
  actions
};

function compareDateDiffirance(date, compareDate) {
  const isDateMoreThanCompareDate = new Date(date) - new Date(compareDate);

  return isDateMoreThanCompareDate;
}

function sortTransactionsByDecreasingTime(a, b) {
  return compareDateDiffirance(a.date, b.date);
}

function sortBetsByDecreasingTime(a, b) {
  return compareDateDiffirance(a.match.date, b.match.date);
}

function sortMessagesByDecreasingTime(a, b) {
  return compareDateDiffirance(a.lastAnswerDate, b.lastAnswerDate);
}

function getSortedData({ compareFunction, data }) {
  return data.sort(compareFunction).reverse();
}

function getLocalItem(key) {
  const item = localStorage[key];

  if (void 0 !== item) {
    return JSON.parse(localStorage[key]);
  }
}
