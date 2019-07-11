// Constants
import {
  sorterTypesNames,
  viewNames,
  roomSectionsHeight,
  userPaymentActions,
  testUserData
} from "@/constants/personalRoom";

import {
  getRandomId,
  transformDate,
  slideTo,
  timeout
} from "@/constants/pureFunctions";
import { requestUrls } from "@/constants/conf";
import popupsNames from "@/constants/popups";

// Components
import UserTransaction from "@/components/user/UserTransaction";
import UserTransactionsHeader from "@/components/user/UserTransactionsHeader";
import FileUpload from "vue-upload-component";
import UserBet from "@/components/user/UserBet";
import SomeBetsTableHead from "@/components/user/SomeBetsTableHead";
import SomeBetStatistic from "@/components/user/SomeBetStatistic";
import ContactCenterTableHead from "@/components/user/ContactCenterTableHead";
import ContactCenterUserMessage from "@/components/user/ContactCenterUserMessage";
import PersonaMessage from "@/components/user/PersonaMessage";

export default {
  el: "#personalRoom",
  components: {
    UserTransaction,
    UserTransactionsHeader,
    FileUpload,
    UserBet,
    SomeBetsTableHead,
    SomeBetStatistic,
    ContactCenterTableHead,
    ContactCenterUserMessage,
    PersonaMessage
  },
  filters: {
    getQuantityPages(data, range, paginationName) {
      return Math.ceil(data.length / range);
    },
    formatSize(size) {
      return `${+(size / 1024).toFixed(2)}mb;`;
    },
    transactionDate(date) {
      return transformDate(date, "LLL");
    }
  },
  data: () => ({
    testUserData,
    personalRoomPopupName: popupsNames.personalRoom,
    newThreadPopupName: popupsNames.newThread,
    personalRoomPopupName: popupsNames.personalRoom,
    transactions: {
      untilDate: "",
      sinceDate: ""
    },
    acceptedBets: {
      untilDate: "",
      sinceDate: ""
    },
    createdBets: {
      untilDate: "",
      sinceDate: ""
    },
    contactCenter: {
      untilDate: "",
      sinceDate: ""
    },
    choosenThread: {
      message: "",
      files: [],
      hint: "",
      status: {
        processed: false,
        success: false,
        error: false
      }
    },
    newThread: {
      hint: ""
    },
    isUserPanelHidden: false,
    avatar: {
      files: []
    },
    window: window,
    userPaymentActions,
    viewNames,
    sorterTypesNames,

    isLoadedData: false
  }),

  delimiters: ["[[", "]]"],

  computed: {
    requestStatus() {
      return this.$store.state.authorization.requestStatus;
    },

    personalRoom() {
      return this.$store.state.personalRoom;
    },
    paginations() {
      return this.$store.state.pagination;
    },

    user() {
      return this.$store.state.personalRoom.user;
    },
    createdBetStatistic() {
      return this.user.statistic["created"];
    },
    acceptedBetStatistic() {
      return this.user.statistic["accepted"];
    },

    transactionActionTypeSorterName() {
      return sorterTypesNames.transactions.type;
    },
    transactionStatusSorterName() {
      return sorterTypesNames.transactions.status;
    },
    transactionBetTypeSorterName() {
      return sorterTypesNames.transactions.bets;
    },

    createdBetsStatusName() {
      return sorterTypesNames.createdBets.status;
    },
    createdBetsGameName() {
      return sorterTypesNames.createdBets.game;
    },

    acceptedBetsStatusName() {
      return sorterTypesNames.acceptedBets.status;
    },
    acceptedBetsGameName() {
      return sorterTypesNames.acceptedBets.game;
    },

    contactCenterStatusSorterName() {
      return sorterTypesNames.contactCenter.status;
    }
  },

  mounted() {
    console.log("log");

    this.sortTransactions();
    this.sortCreatedBets();
    this.sortAcceptedBets();
    this.sortContactCenter();
  },

  methods: {
    getRandomId,

    setLoadedData() {
      this.isLoadedData = true;
    },

    sortTransactions() {
      const {
        personalRoom,
        transactionStatusSorterName,
        transactionActionTypeSorterName,
        transactionBetTypeSorterName,
        transactions
      } = this;
      const { untilDate, sinceDate } = transactions;

      const betType =
        personalRoom[transactionBetTypeSorterName].currentSorterType;
      const status =
        personalRoom[transactionStatusSorterName].currentSorterType;
      const actionType =
        personalRoom[transactionActionTypeSorterName].currentSorterType;

      const sorterSettings = {
        untilDate,
        sinceDate,
        status,
        actionType,
        betType
      };

      this.$store.commit("sorters/setSorterSettings", {
        name: "transactions",
        sorterSettings
      });

      if (this.$refs.personalRoom) {
        this.$refs.personalRoom.onChangeTransactionsSorterSettings(
          sorterSettings
        );
      }
    },

    sortCreatedBets() {
      const {
        personalRoom,
        createdBetsStatusName,
        createdBetsGameName,
        createdBets
      } = this;
      const { untilDate, sinceDate } = createdBets;

      const status = personalRoom[createdBetsStatusName].currentSorterType;
      const game = personalRoom[createdBetsGameName].currentSorterType;

      const sorterSettings = {
        untilDate,
        sinceDate,
        status,
        game
      };

      this.$store.commit("sorters/setSorterSettings", {
        name: "createdBets",
        sorterSettings
      });
      if (this.$refs.personalRoom) {
        this.$refs.personalRoom.onChangeCreatedBetsSorterSettings(
          sorterSettings
        );
      }
    },

    sortAcceptedBets() {
      const {
        personalRoom,
        acceptedBetsStatusName,
        acceptedBetsGameName,
        acceptedBets
      } = this;

      const { untilDate, sinceDate } = acceptedBets;

      const status = personalRoom[acceptedBetsStatusName].currentSorterType;
      const game = personalRoom[acceptedBetsGameName].currentSorterType;

      const sorterSettings = {
        untilDate,
        sinceDate,
        status,
        game
      };

      this.$store.commit("sorters/setSorterSettings", {
        name: "acceptedBets",
        sorterSettings
      });
      if (this.$refs.personalRoom) {
        this.$refs.personalRoom.onChangeAcceptedBetsSorterSettings(
          sorterSettings
        );
      }
    },

    sortContactCenter() {
      const {
        personalRoom,
        contactCenterStatusSorterName,
        contactCenter
      } = this;
      const { untilDate, sinceDate } = contactCenter;

      const status =
        personalRoom[contactCenterStatusSorterName].currentSorterType;

      const sorterSettings = {
        untilDate,
        sinceDate,
        status
      };

      this.$store.commit("sorters/setSorterSettings", {
        name: "contactCenter",
        sorterSettings
      });
      if (this.$refs.personalRoom) {
        this.$refs.personalRoom.onChangeContactCenterSorterSettings(
          sorterSettings
        );
      }
    },

    openLinkInNewWindowIfAvailable(event) {
      return isAvailabled => {
        if (isAvailabled) {
          const url = event.target.href;

          window.open(url);
        }
      };
    },

    getRangeOfData(data, dataModuleName) {
      const dataInfo = this.$store.state.pagination[dataModuleName];

      const dataPerPage = dataInfo.dataPerPage;
      const currentPage = dataInfo.currentPage;

      return data.slice(
        dataPerPage * (currentPage - 1),
        dataPerPage * currentPage
      );
    },

    switchContactCenterPage(pageNumber, dataName) {
      this.$store.dispatch(
        "pagination/switchContactCenterPageAndCloseThreadIfNeeded",
        {
          choosenPage: pageNumber,
          dataName
        }
      );
    },

    switchPage(pageNumber, dataName) {
      this.$store.commit(`pagination/changePaginationPage`, {
        choosenPage: pageNumber,
        dataName
      });
    },

    changeUserPaymentAction({ userAction, availabledPayments }) {
      return () => {
        this.$store.dispatch("personalRoom/showUserPaymentsByChoosenAction", {
          userAction,
          availabledPayments
        });
      };
    },

    switchView(viewName) {
      return () => {
        this.$nextTick(() => {
          if (window.innerWidth < 580) {
            timeout(() => {
              slideTo({
                selector: ".personalRoom section"
              });
            }, 250);
          }
        });

        this.$store.dispatch("personalRoom/openView", viewName);
      };
    },

    switchSorterTypeAndSort({ sorterTypesName, sortFunctionName }) {
      // В первый слой передаётся имя, чтобы привязать последующее передаваемое выбранное значение используя коммит.
      return choosenSorterType => {
        // Внутри  компонента  сортировки, для кнопки выбора типа сортировки вызвается функция onChooseSorterType(sorterTypeName) с переданым первым позиционным аргументом значением с нужным типом.
        return () => {
          // Состояние меняется по нажатию
          this.$store.commit("personalRoom/changeCurrentSorterType", {
            sorterTypesName,
            choosenSorterType
          });

          try {
            this[sortFunctionName]();
          } catch (e) {
            throw new Error(
              `There is not such function for sorting data. See error: ${e}`
            );
          }
        };
      };
    },

    switchSorterTypesDisplayState(sorterTypesName) {
      return () => {
        this.$store.commit(
          "personalRoom/switchListSorterTypeState",
          sorterTypesName
        );
      };
    },

    switchSorterTypesDisplayState(sorterTypesName) {
      return () => {
        this.$store.commit(
          "personalRoom/switchListSorterTypeState",
          sorterTypesName
        );
      };
    },

    openNewThreadPopup() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: true,
        popupName: this.newThreadPopupName
      });
    },

    closeNewThreadPopup() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: false,
        popupName: this.newThreadPopupName
      });
    },

    avatarFilter(newFile, oldFile, prevent) {
      if (newFile && !oldFile) {
        if (!/\.(gif|jpg|jpeg|png|webp)$/i.test(newFile.name)) {
          // this.alert("Your choice is not a picture");
          return prevent();
        }
      }
      if (newFile && (!oldFile || newFile.file !== oldFile.file)) {
        newFile.url = "";
        let URL = window.URL || window.webkitURL;
        if (URL && URL.createObjectURL) {
          newFile.url = URL.createObjectURL(newFile.file);
        }
      }
    },

    inputFilter(newFile, oldFile, prevent) {
      if (newFile && !oldFile) {
        // Before adding a file
        // Filter system files or hide files
        if (/(\/|^)(Thumbs\.db|desktop\.ini|\..+)$/.test(newFile.name)) {
          return prevent();
        }

        // Filter php html js file
        if (/\.(php5?|html?|jsx?)$/i.test(newFile.name)) {
          return prevent();
        }
        newFile.url = "";
        let URL = window.URL || window.webkitURL;

        if (URL && URL.createObjectURL) {
          newFile.url = URL.createObjectURL(newFile.file);
        }
      }
    },

    inputFile(newFile, oldFile) {
      if (newFile && !oldFile) {
        // Add.
      }
      if (newFile && oldFile) {
        // Update.
      }
      if (!newFile && oldFile) {
        // Remove.
      }
    },

    showThreadHint({ hideDelay = 2500, hint, moduleName = "choosenThread" }) {
      const thread = this[moduleName];

      if (thread.hintTimer) {
        clearTimeout(thread.hintTimer);
      }

      thread.hint = hint;

      thread.hintTimer = setTimeout(() => {
        if (thread.hint) {
          thread.hint = "";
        }
      }, hideDelay);
    },

    removeFile(fileIndex) {
      return () => {
        const thread = this.choosenThread;
        const files = thread.files;

        if (files.length) {
          thread.files = [
            ...files.slice(0, fileIndex),
            ...files.slice(fileIndex + 1)
          ];
        }
      };
    },

    postUserMessage() {
      this.$refs.upload.active = true;

      const thread = this.choosenThread;
      const timestamp = new Date();
      const threadId = this.user.choosenThread.data.threadId;

      if (!thread.message && !thread.files.length) {
        this.showThreadHint({
          hint: "Нельзя просто так взять и отправить пустое сообщение."
        });

        return false;
      }

      const data = {
        files: thread.files,
        message: thread.message,
        userID: this.user.id,
        date: timestamp,
        threadId
      };

      Promise.resolve({
        status: {
          code: 200
        }
      })
        .then(response => {
          thread.status.processed = true;

          const lastChoosenThreadPage = this.paginations.choosenThread
            .quantityPages;

          timeout(() => {
            switch (response.status.code) {
              case 200:
                this.$store.dispatch(
                  "personalRoom/updateDialogAndSwitchPageIfNeeded",
                  {
                    message: data.message,
                    images: data.files,
                    date: timestamp,
                    lastChoosenThreadPage,
                    threadId
                  }
                );

                thread.status = {
                  processed: false,
                  success: true
                };

                thread.message = "";
                thread.files = [];

                break;
              default:
                thread.status = {
                  processed: false,
                  error: true
                };

                this.showThreadHint({
                  hint: "Не удалось отправить сообщение."
                });
            }

            // Сброс состояния ответа от сервера.
            timeout(() => {
              thread.status = {
                processed: false,
                success: false,
                error: false
              };
            }, 2500);
          }, 1500);
        })
        .catch(error => {
          this.showThreadHint({
            hint:
              "Не удалось отправить сообщение из-за внутренней ошибки сервера."
          });
        });
    },

    log(message) {
      console.log(message);
    },

    makeNewThread() {
      log("make-new-thread");
      const user = this.user;
      const newThread = user.newThread;
      const message = newThread.message;
      const title = newThread.title;

      if (!message || !title) {
        this.showThreadHint({
          hint: "Чтобы создать тему, следует заполнить оба поля",
          moduleName: "newThread"
        });

        return false;
      }

      // Month/Day/Year Hours/Minutes
      const timestamp = moment().format("MM/DD/YYYY HH:mm");
      const max = 9999;
      const min = 1000;
      const threadId = getRandomId(min, max);

      let requestUrl = "/api/current";
      const requestHeaders = {
        "X-CSRF-Token": $store.state.csrftoken
      };
      const requestParams = {
        lastAnswerDate: timestamp,
        userID: user.id,
        threadId,
        message,
        title
      };

      const requestOptions = {
        url: requestUrl,
        data: requestData,
        method: "POST",
        headers: requestHeaders
      };

      // const request = axios(requestOptions);

      const request = window.Promise.resolve({
        status: "ok"
      });

      window.setTimeout(() => {
        request.then(response => {
          const isError = response.status !== "ok";

          const newThreadData = {
            status: {
              message: "Идёт обработка проблемы",
              name: ""
            },
            lastAnswerDate: timestamp,
            hash: threadId,
            date: timestamp,
            title,
            threadId
          };

          this.$store.dispatch("personalRoom/makeNewThreadAndOpenThread", {
            threadData: newThreadData,
            threadId,
            message
          });
        });
      }, 1000);
    },

    getCurrentFormatedTime(format) {
      return moment()
        .tz(this.$store.state.timezone)
        .format(format);
    }
  }
};
