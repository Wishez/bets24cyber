import "es6-promise/auto";
import Vue from "vue";
import storePlugin from "@/store/storePlugin";
import VueLazyload from "vue-lazyload";
import PortalVue from "portal-vue";
import VueImg from "v-img";
import axios from "axios";
import VueAxios from "vue-axios";
import "@/plugins/componentsHandler";
import "@/plugins/global";
import "@/plugins/globalFilters";
import "@/plugins/storage";
import bus from "@/plugins/globalBus";

import "./assets/sass/fonts.sass";
import "./assets/sass/main.sass";

import dummy from "@/assets/images/dummy.png";

Vue.use(bus);
Vue.use(VueImg);
Vue.use(PortalVue);
Vue.use(storePlugin);
Vue.use(VueLazyload, {
  lazyComponent: true,
  preLoad: 1.3,
  error: dummy
});
Vue.use(VueAxios, axios);

// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
Vue.config.productionTip = false;

[
  "header",
  "banner",
  "main",
  "footer",
  "personalRoom",
  "matchPopup",
  "notificationPopup"
].forEach(sectionName => {
  window.ComponentsHandler.initSection(sectionName);
});
