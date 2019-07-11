const siteApi = ""; //http://localhost:3000';

export const subscribtionUrl = "/api/v1/user/subscribe/";

export const requestUrls = {
  subscribtion: "/api/v1/user/subscribe/",
  acceptMessage: "/api/v1/user/acceptMessage/",
  deleteMessage: "/api/v1/user/deleteMessage/",
  acceptBet: "/api/v1/user/acceptBet/",
  deleteBet: "/api/v1/user/deleteBet/",
  returnBet: "/api/v1/user/returnBet/",
  postUserMessage: "/api/v1/user/postUserMessage/",
  uploadUserAvatarUrl: "/api/v1/user/uploadUserAvatarUrl/",
  createNewThreadUrl: "/api/v1/user/createNewThread/"
};

export const games = {
  dota: "dota",
  csgo: "csgo",
  lol: "lol",
  hs: "hs",
  ow: "ow"
};
export const gamesNames = {
  dota: "Дота 2",
  csgo: "CSGO",
  lol: "League of Legends",
  hs: "Hearthstone",
  ow: "Overwatch"
};

export const storeModulesIds = {
  matches: "matches",
  bets: "bets"
};
export const activeGamesIds = {
  matches: "matchesActiveGameId",
  bets: "betsActiveGameId"
};

export default siteApi;
