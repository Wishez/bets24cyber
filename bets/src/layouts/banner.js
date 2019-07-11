import banner from "@/assets/images/cybersport.png";
import formsNames from "@/constants/authorization";

export default {
  el: "#banner",
  data: () => ({
    banner
  }),
  methods: {
    openRegistrationForm() {
      this.$store.dispatch(
        "popups/openAuthPopupAndShowForm",
        formsNames.registration
      );
    }
  }
};
