import store from "./index";

export default {
  store,
  install(Vue) {
    Vue.prototype.$store = store;
  }
};
