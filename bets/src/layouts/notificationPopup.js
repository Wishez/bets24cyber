import popupsNames from "@/constants/popups";
import formsNames from "@/constants/authorization";

export default {
  el: "#notificationPopup",
  computed: {
    window() {
      return window;
    },

    matchNotification() {
      return this.$store.state.authorization[formsNames.notification];
    },

    requestStatus() {
      return this.$store.state.authorization.requestStatus;
    },

    newUserBet() {
      return this.$store.state.personalRoom.user.newUserBet;
    }
  },
  methods: {
    log(message) {
      console.log(message);
    },

    openAuthPopup(formName) {
      return () => {
        this.closeNotificationPopup();

        this.$nextTick(() => {
          this.$store.dispatch("popups/openAuthPopupAndShowForm", formName);
        });
      };
    },

    openNotificationPopup() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: true,
        popupName: this.noftificationPopupName
      });
    },

    closeNotificationPopup() {
      this.$store.dispatch("popups/closeNotificationPopup");
    }
  },
  data: () => ({
    noftificationPopupName: popupsNames.notification
  })
};
