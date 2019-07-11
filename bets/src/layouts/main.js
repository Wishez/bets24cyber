import { games, storeModulesIds, activeGamesIds } from "@/constants/conf";
import { csgo, dota, hs, lol, ow, cup } from "@/assets/images/icons";

import { timer } from "@/constants/pureFunctions";
import generatedMatches from "@/constants/bets";

export default {
  el: "#main",
  mounted() {
    this.initDefaultGameChoiceForSorters();
  },

  computed: {
    sortWithDelay() {
      return timer(this.sortBets);
    },
    window() {
      return window;
    },
    betsSorter() {
      return this.$store.state.sorters.betsSorter;
    }
  },

  methods: {
    initDefaultGameChoiceForSorters() {
      [storeModulesIds.bets, storeModulesIds.matches].forEach(moduleId => {
        this.$store.commit(
          `${moduleId}/switchGameId`,
          localStorage[activeGamesIds[moduleId]] || "dota"
        );
      });
    },

    log(message) {
      console.log(message);
    },

    showAllGames(moduleId) {
      this.changeGame("all", moduleId)();
    },

    changeGame(choosenGameId, moduleId) {
      return () => {
        this.$store.commit(`${moduleId}/switchGameId`, choosenGameId);

        localStorage[moduleId] = choosenGameId;
      };
    },

    switchSorterTypesState() {
      this.$store.commit("bets/switchSorterTypesState");
      this.cacheBetsSorter();
    },

    // Смена типа сортировки.
    switchSorterTypeAndSort(choosenType) {
      return () => {
        this.$store.dispatch(
          "bets/choiceSorterTypeAndCloseList",
          choosenType.name
        );

        this.$store.commit("sorters/setBetsType", choosenType);

        this.sortBets();
      };
    },

    sortBets() {
      this.$refs.betsSorter.onChangeSorterSettings(this.betsSorter);
      this.cacheBetsSorter();
    },

    cacheBetsSorter() {
      this.$nextTick(() => {
        $storage.set("betsSorter", this.betsSorter);
      });
    }
  },

  mounted() {
    this.cacheBetsSorter();
    this.log(this.refs.main);
    this.$emit("on-loaded-main-section");
  },
  data: () => ({
    generatedMatches,
    matchesModuleId: storeModulesIds.matches,
    betsModuleId: storeModulesIds.bets,

    // Slider
    responsive: {
      1140: {
        items: 4
      },
      992: {
        items: 3
      },
      769: {
        items: 2,
        controls: true
      },
      480: {
        items: 1
      },
      0: {
        controls: false
      }
    },
    // Game Sorter data
    games: [
      {
        id: games.dota,
        image: dota,
        label: "Dota 2"
      },
      {
        id: games.csgo,
        image: csgo,
        label: "Csgo"
      },
      {
        id: games.lol,
        image: lol,
        label: "League of Legends"
      },
      {
        id: games.hs,
        image: hs,
        label: "Hearth Stone"
      },
      {
        id: games.ow,
        image: ow,
        label: "Owerwatch"
      }
    ]
  })
};
