import popupsNames from "@/constants/popups";
import formsNames from "@/constants/authorization";

import TeamStatusBar from "@/components/TeamStatusBar";
import BetsMatchPopupHeader from "@/components/BetsMatchPopupHeader";
import BetsMatchPopupBetsHeader from "@/components/BetsMatchPopupBetsHeader";
import BetsMatchPopupLabels from "@/components/BetsMatchPopupLabels";

export default {
  el: "#matchPopup",
  components: {
    BetsMatchPopupHeader,
    BetsMatchPopupBetsHeader,
    BetsMatchPopupLabels,
    TeamStatusBar
  },
  computed: {
    window() {
      return window;
    },
    notification() {
      return this.$store.state.authorization[formsNames.notification];
    },
    requestStatus() {
      return this.$store.state.authorization.requestStatus;
    }
  },
  methods: {
    setInitedStateOfPopup() {
      this.$store.commit("popups/updateUpdatingDataState", {
        isUpdated: true,
        popupName: this.matchPopupName
      });
    },

    rerenderMatchPopup() {
      window.ComponentsHandler.initSection("matchPopup");
    },

    log(message) {
      console.log(message);
    },

    openNotificationPopup() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: true,
        popupName: this.noftificationPopupName
      });
    },

    closeNotificationPopup() {
      this.$store.dispatch("popups/closeNotificationPopup");
    },

    closeMatchPopup() {
      this.$store.dispatch("popups/closeMatchPopupAndCleanState");
    }
  },
  data: () => ({
    matchPopupName: popupsNames.match,
    matchNoftificationPopupName: popupsNames.notification
  })
};
