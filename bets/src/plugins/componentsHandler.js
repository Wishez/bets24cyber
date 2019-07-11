import Vue from "vue";

import headerConfig from "@/layouts/header";
import bannerConfig from "@/layouts/banner";
import mainConfig from "@/layouts/main";
import footerConfig from "@/layouts/footer";
import matchPopupConfig from "@/layouts/matchPopup";
import personalRoomConfig from "@/layouts/personalRoom";
import notificationPopupConfig from "@/layouts/notificationPopup";

(function() {
  const configuration = [
    {
      config: headerConfig,
      name: "header"
    },
    {
      config: bannerConfig,
      name: "banner"
    },
    {
      config: footerConfig,
      name: "footer"
    },
    {
      config: mainConfig,
      name: "main"
    },
    {
      config: personalRoomConfig,
      name: "personalRoom"
    },
    {
      config: matchPopupConfig,
      name: "matchPopup"
    },
    {
      config: notificationPopupConfig,
      name: "notificationPopup"
    }
  ];

  const sections = (function() {
    return configuration.reduce((sections, section) => {
      sections[section.name] = _prepareSection(section.config);

      return sections;
    }, {});
  })();

  function _prepareSection(config) {
    return () => {
      new Vue(config);
    };
  }

  function initSection(sectionName) {
    try {
      sections[sectionName]();
    } catch (e) {
      console.log(`Нет секции с именем ${sectionName}, или ${e}`);
    }
  }

  window.ComponentsHandler = {
    initSection
  };
})();
