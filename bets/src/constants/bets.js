import { international, mdl } from "@/assets/images/league";
import { eg, newbee, wings, teamliquid } from "@/assets/images/logos";
import { eu, cn } from "@/assets/images/flags";

const generatedMatches = (function() {
  let bets = [];

  for (let i = 0; i < 6; i++) {
    bets = [
      ...bets,
      {
        lastRatioValues: {
          left: -1,
          right: -1
        },
        leftTeam: {
          ratio: "TBA",
          name: "Neewbee",
          country: cn,
          logo: newbee
        },
        rightTeam: {
          ratio: "TBA",
          name: "Evil Geniuses",
          country: cn,
          logo: eg
        },
        league: {
          name: "The International 2018",
          logo: international
        },
        match: {
          date: "08.08.2017 19:00",
          url: "/api/cuurent/match/4321",
          isOnline: false,
          isCompleted: true,
          score: {
            left: 0,
            right: 0
          }
        }
      },
      {
        lastRatioValues: {
          left: 2.01,
          right: 2
        },
        leftTeam: {
          ratio: 2.2,
          name: "Team Liquid",
          country: eu,
          logo: teamliquid
        },
        rightTeam: {
          ratio: 1.86,
          name: "Wings Gaming",
          country: cn,
          logo: wings
        },
        league: {
          name: "MarsDota2League",
          logo: mdl
        },
        match: {
          date: "08.08.2017 19:00",
          url: "/api/cuurent/match/1234",
          isOnline: true,
          isCompleted: false,
          score: {
            left: 0,
            right: 0
          }
        }
      }
    ];
  }

  return () => bets;
})();

export default generatedMatches;
