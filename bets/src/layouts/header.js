import { user } from "@/assets/images/icons";
import { timeout } from "@/constants/pureFunctions";
import popupsNames from "@/constants/popups";
import formsNames from "@/constants/authorization";
import UserAuthorization from "@/components/user/UserAuthorization";

export default {
  el: "#header",
  delimiters: ["[[", "]]"],
  components: {
    UserAuthorization
  },
  data: () => ({
    isLanguagesShown: false,
    isPageLoaded: false,
    currentLanguage: localStorage.currenUserLanguage || "RU",
    userBalance: 245.53,
    userIcon: user,
    accessibleTime: "",
    currentTime: "",
    isTimezonesShown: false,
    formsNames
  }),
  computed: {
    window() {
      return window;
    },
    authorization() {
      return this.$store.state.authorization;
    },
    login() {
      return this.authorization[formsNames.login];
    },
    registration() {
      return this.authorization[formsNames.registration];
    },
    recoverPassword() {
      return this.authorization[formsNames.recoverPassword];
    }
  },
  beforeMount() {
    this.$nextTick(() => {
      localforage.getItem("userAuthorizationData").then(cacheUserData => {
        if (cacheUserData) {
          this.$store.commit("personalRoom/setUserPersonalData", cacheUserData);
        }
      });

      this.updateTime();
    });
  },
  mounted() {
    this.$nextTick(() => {
      timeout(() => {
        this.isPageLoaded = true;
      }, 250);
    });
    setInterval(() => {
      this.updateTime();
    }, 1000);

    this.cacheTimezone(this.$store.state.timezone);
  },
  methods: {
    chooseTimezone(timezone) {
      return () => {
        this.$store.dispatch("changeTimezone", timezone);
        this.switchTimezonesListState();
        this.cacheTimezone(timezone);
      };
    },

    cacheTimezone(timezone) {
      this.$nextTick(() => {
        $storage.set("choosenTimezone", timezone);
        this.$bus.$emit("update-timezone");
      });
    },

    switchTimezonesListState() {
      this.isTimezonesShown = !this.isTimezonesShown;
    },

    submitForm({ formName, options, headers, responseCallback }) {
      this.log(login);

      const requestOptions = {
        headers: {
          "X-CSRF-Token": $store.state.csrftoken
        }
      };

      this.$store.commit("authorization/switchRequestingState", true);

      const request = Promise.resolve({
        status: "ok",
        userData: {
          balance: 9999.99,
          name: "shiningfinger",
          avatar: "/images/personalRoom/avatar.png",
          id: 139452
        },
        isLogged: true
      });

      setTimeout(() => {
        request.then(response => {
          const responseOptions = {
            userData: {
              ...response.userData
            },
            isError: status.ok ? false : true,
            formName
          };

          this.$store.dispatch(
            "authorization/showUserDataIfUserLogged",
            responseOptions
          );
        });
      }, 1500);
    },

    log(message) {
      console.log(message);
    },

    switchForm(formName) {
      return () => {
        this.$store.commit("authorization/switchForm", formName);
      };
    },

    openAuthPopup(formName) {
      return () => {
        this.$store.dispatch("popups/openAuthPopupAndShowForm", formName);
      };
    },

    getCurrentTime(format) {
      return moment()
        .tz(this.$store.state.timezone)
        .format(format);
    },

    setTime(property, format) {
      this.$set(this, property, this.getCurrentTime(format));
    },

    updateTime() {
      this.setTime("currentTime", "HH:mm z");
      this.setTime("accessibleTime", "L");
    },

    switchLanguageState() {
      this.$set(this, "isLanguagesShown", !this.isLanguagesShown);
    },

    chooseLanguage(languageName) {
      return () => {
        localStorage.currenUserLanguage = languageName;
      };
    },

    openPersonalRoom() {
      this.$store.commit("popups/switchPopupState", {
        isOpened: true,
        popupName: popupsNames.personalRoom
      });
    }
  }
};
