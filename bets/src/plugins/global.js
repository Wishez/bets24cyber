import Vue from "vue";

import Icon from "vue-awesome/components/Icon";
import "vue-awesome/icons/list-ul";
import "vue-awesome/icons/envelope";
import VueTinySlider from "vue-tiny-slider";
// // Components
import SearchIcon from "@/components/SearchIcon";
import ChevronIcon from "@/components/ChevronIcon";
import ButtonIcon from "@/components/ButtonIcon";

import BaseButton from "@/components/BaseButton";
import CheckButton from "@/components/CheckButton";

import LazyImage from "@/components/LazyImage";

import SectionContainer from "@/components/SectionContainer";
import MatchInfo from "@/components/MatchInfo";
import BetsTable from "@/components/BetsTable";
import BetInfo from "@/components/BetInfo";
import BetsMatchPopup from "@/components/BetsMatchPopup";

// import GameSorter from "@/components/GameSorter";
import BetsSorter from "@/components/BetsSorter";
import SorterDate from "@/components/sorter/SorterDate";
import SorterTypes from "@/components/sorter/SorterTypes";

import FooterGames from "@/components/FooterGames";
import PersonalRoom from "@/components/user/PersonalRoom";

import FadeTranslateTransitionGroup from "@/components/FadeTranslateTransitionGroup";

import SiteNavigation from "@/layouts/SiteNavigation";

import ModalContainer from "@/components/ModalContainer";
import MatchStreams from "@/components/MatchStreams";
import MatchTeams from "@/components/MatchTeams";

import DataPagination from "@/components/user/DataPagination";
import IconClose from "@/components/icons/IconClose";
import ViewPreloader from "@/components/icons/ViewPreloader";
import VueUploadComponent from "vue-upload-component";

Vue.component("icon-preloader", ViewPreloader);
Vue.component("icon-close", IconClose);
Vue.component("file-upload", VueUploadComponent);

Vue.component("icon", Icon);
Vue.component("tiny-slider", VueTinySlider);

Vue.component("modal-container", ModalContainer);

Vue.component("match-teams", MatchTeams);
Vue.component("match-streams", MatchStreams);

// Registration of components
Vue.component("site-navigation", SiteNavigation);
Vue.component("base-button", BaseButton);
Vue.component("button-icon", ButtonIcon);
Vue.component("search-icon", SearchIcon);

Vue.component("chevron-icon", ChevronIcon);
Vue.component("lazy-image", LazyImage);

Vue.component("match-info", MatchInfo);

Vue.component("bets-sorter", BetsSorter);
Vue.component("sorter-types", SorterTypes);
Vue.component("sorter-date", SorterDate);

Vue.component("bets-table", BetsTable);
Vue.component("bets-match-popup", BetsMatchPopup);
Vue.component("bet-info", BetInfo);

Vue.component("fade-translate-transition-group", FadeTranslateTransitionGroup);

Vue.component("section-container", SectionContainer);
Vue.component("personal-room", PersonalRoom);

Vue.component("data-pagination", DataPagination);
Vue.component("footer-games", FooterGames);
