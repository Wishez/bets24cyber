import { eg, vp } from "@/assets/images/logos";

function generateMatches() {
  let matches = [];

  for (let i = 0; i < 10; i++) {
    matches = [
      ...matches,
      {
        lastRatioValues: {
          left: 2,
          right: 1.5
        },
        leftTeam: {
          ratio: 1.42,
          name: "EG",
          logo: eg
        },
        rightTeam: {
          ratio: 2.27,
          name: "VP",
          logo: vp
        },
        league: {
          name: "MarsDota2League",
          stage: "Group Stage"
        },
        match: {
          date: "08.08.2017 18:00",
          url: "api/current/match/1234/"
        },
        game: {
          id: "dota"
        }
      }
    ];
  }

  return matches;
}

const state = {
  activeGameId: "dota",
  matches: generateMatches()
};

const mutations = {
  switchGameId(state, activeGameId) {
    state.activeGameId = activeGameId;
  },

  updateMatches(state, newMatches) {
    state.matches = newMatches;
  }
};

export default {
  namespaced: true,
  state,
  mutations
};
