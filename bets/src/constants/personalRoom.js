import { gamesNames } from "@/constants/conf";
import { dota } from "@/assets/images/icons";
import { vp, teamliquid, lumi, lgd } from "@/assets/images/logos";
import { timeout } from "@/constants/pureFunctions";

export const viewNames = {
  balance: "balance",
  acceptedBets: "acceptedBets",
  contactCenter: "contactCenter",
  createdBets: "createdBets",
  userMessages: "userMessages"
};

export const userPaymentActions = {
  deposit: "deposit",
  withdrawal: "withdrawal"
};

export const sorterTypesNames = {
  transactions: {
    status: "transactionStatusSorter",
    type: "transactionTypeSorter",
    bets: "betsSorter"
  },
  createdBets: {
    status: "createdBetsStatusSorter",
    game: "createdBetsGameSorter"
  },
  acceptedBets: {
    status: "acceptedBetsStatusSorter",
    game: "acceptedBetsGameSorter"
  },
  contactCenter: {
    status: "contactCenterStatusSorter"
  }
};

export const roomSectionsHeight = {
  [viewNames.balance]: 1232,
  [viewNames.acceptedBets]: 1232,
  [viewNames.contactCenter]: 1232,
  [viewNames.createdBets]: 1232,
  [viewNames.userMessages]: 1232
};

//  Кэширую миксин для создания ставок.
export const generateSomeBetsSorterOptions = (function() {
  const someBetsSorter = {
    games: {
      all: [],
      default: { id: 0, name: "Игра" }
    },
    statuses: {
      all: [],
      default: { id: 0, name: "Статус" }
    }
  };

  return (sorterTypesModuleName, sorterName) => {
    return asSorter({
      types: someBetsSorter[sorterName].all,
      currentSorterType:
        localStorage[sorterTypesModuleName] ||
        someBetsSorter[sorterName].default
    });
  };
})();

export const testUserData = {
  transactions: [
    {
      date: `03.03.2018 20:01`,
      action: "Депозит на виртуальный счёт",
      status: {
        text: "Выполнено",
        processed: false
      },
      sum: 205.13,
      commission: 2.05,
      rest: 235.11
    },
    {
      date: `03.05.2018 21:00`,
      action: "Вывод средств на виртуальный счёт",
      status: {
        text: "Ожидается",
        processed: true
      },
      sum: 322,
      commission: 3.21795934285575001219,
      rest: 369.06069321893433432457
    }
  ],
  createdBets: [
    {
      bet: {
        id: 2466,
        status: "Открыто",
        ratio: 2.1,
        sum: 300,
        acceptedSum: 500.1
      },
      game: {
        image: dota
      },
      match: {
        noteAboutEndMatch: "Снос башни на 20-й минуте",
        date: "04/12/2018 20:45"
      },
      leftTeam: {
        logo: teamliquid,
        name: "Team Liquid"
      },
      rightTeam: {
        logo: vp,
        name: "Virtus Pro"
      },
      winTeam: {
        logo: teamliquid,
        name: "Team Liquid"
      },
      buttonsDisplay: {
        accept: true,
        cancel: false,
        delete: true
      }
    },
    {
      bet: {
        id: 322,
        status: "Открыто",
        ratio: 0,
        sum: 322,
        acceptedSum: 322
      },
      game: {
        image: dota
      },
      match: {
        noteAboutEndMatch: "Кто-то сделал неправильную ставку",
        date: "04/13/2018 18:45"
      },
      leftTeam: {
        logo: lumi,
        name: "Luminosity Gaming"
      },
      rightTeam: {
        logo: "http://i.imgur.com/XjSMkWV.png",
        name: "Rox.Kis"
      },
      winTeam: {
        logo: lumi,
        name: "Luminosity Gaming"
      },
      buttonsDisplay: {
        accept: true,
        cancel: false,
        delete: true
      }
    }
  ],
  acceptedBets: [
    {
      bet: {
        id: 3464,
        status: "Открыто",
        ratio: 2.1,
        sum: 300,
        acceptedSum: false
      },
      game: {
        image: dota
      },
      match: {
        date: "04/12/2018 20:15",
        noteAboutEndMatch: "Снос башни на 20-й минуте"
      },
      leftTeam: {
        logo: teamliquid,
        name: "Team Liquid"
      },
      rightTeam: {
        logo: vp,
        name: "Virtus Pro"
      },
      winTeam: {
        logo: teamliquid,
        name: "Team Liquid"
      },
      buttonsDisplay: {
        accept: true,
        cancel: true,
        delete: true
      }
    },
    {
      bet: {
        id: 6542,
        status: "Открыто",
        ratio: 1.4,
        sum: 1488,
        acceptedSum: false
      },
      game: {
        image: dota
      },
      match: {
        noteAboutEndMatch: "Снос башни на 20-й минуте",
        date: "04/13/2018 18:45"
      },
      leftTeam: {
        logo: lumi,
        name: "Luminosity Gaming"
      },
      rightTeam: {
        logo: lgd,
        name: "LGD.Forever_Young"
      },
      winTeam: {
        logo: lgd,
        name: "LGD.Forever_Young"
      },
      buttonsDisplay: {
        accept: true,
        cancel: true,
        delete: true
      }
    }
  ],
  contactCenter: [
    {
      threadId: 9543,
      lastAnswerDate: "04/28/2018 17:20",
      title: "Не вижу пополнения лицевого счета",
      status: {
        message: "Ожидание ответа от",
        name: "Robert Ivanov"
      }
    },
    {
      threadId: 1488,
      lastAnswerDate: "04/29/2018 17:20",
      title: "Не могу вывести деньги",
      status: {
        message: "Ожидание ответа от",
        name: "ESXbetting"
      }
    }
  ]
};

export const userTestDialogs = {
  9543: {
    id: 9543,
    companionName: "ESXbetting",
    dialog: [
      {
        isUser: true,
        message:
          "Если бы очки квиддича не шли в зачёт Кубка школы, то практически всем было бы наплевать на эту систему баллов. Она превратилась бы в невразумительное соревнование для учеников вроде вас и мисс Грейнджер.",
        date: "04/20/2018 13:34",
        images: []
      },
      {
        isUser: false,
        message:
          "Невозможное часто обладает качеством целостности, то есть тем качеством, которого недостаёт неправдоподобному».",
        date: "04/20/2018 13:35",
        images: []
      },
      {
        isUser: true,
        message:
          "Иногда нужно уходить от вопросов, даже если тебе нечего скрывать, иначе в другой раз, когда тебе будет, что скрывать, и придётся уходить от вопросов, станет очевидно, что у тебя появилась тайна.",
        date: "05/20/2018 00:37",
        images: []
      },
      {
        isUser: true,
        message:
          "Причина в том, что люди не задумываются перед тем, как причинить боль. Они не пытаются представить, что им самим тоже могут сделать больно, что они сами тоже могут пострадать от своих собственных злодеяний.",
        date: "05/20/2018 00:40",
        images: []
      },
      {
        isUser: false,
        message:
          "Ты понимаешь, что рассказать всё ближайшим друзьям означает рассказать всем, потому что тем самым ты доверяешь секрет не только им, но и всем, кому они доверяют.",
        date: "05/20/2018 14:00",
        images: []
      },
      {
        isUser: false,
        message:
          "Если тебя так сильно волнует мнение других людей, и ты чувствуешь себя несчастной всякий раз, когда их представления о тебе не совпадают с твоими собственными, то ты обречена быть несчастной всегда.",
        date: "05/20/2018 14:05",
        images: []
      }
    ]
  },
  1488: {
    id: 1488,
    companionName: "Shinig Finger",
    dialog: [
      {
        isUser: true,
        message:
          "У меня проблемы с выводом выигрышных средств. Что мне делать?",
        date: "04/20/2018 13:34",
        images: []
      },
      {
        isUser: false,
        message:
          "Чем больше ты пытаешься оправдаться перед такими людьми, тем больше они уверяются в своём праве судить тебя.",
        date: "04/20/2018 13:35",
        images: []
      },
      {
        isUser: false,
        message:
          "Твой мозг представляет одну птичку, пойманную в ловушку масляного пятна, и именно эта картина определяет, сколько ты заплатишь.",
        date: "05/20/2018 00:37",
        images: []
      },
      {
        isUser: false,
        message:
          "Большинство людей вообще не замечает моральной проблемы, пока кто-либо не обратит на неё их внимание.",
        date: "05/20/2018 00:40",
        images: []
      },
      {
        isUser: false,
        message:
          "Если когда-нибудь мы сможем поладить с существами рождёнными из кристаллов, насколько же глупо будет не поладить с маглорождёнными.",
        date: "05/20/2018 14:00",
        images: []
      },
      {
        isUser: true,
        message:
          "Спасибо! Думаю, это были полезные советы для решения моей проблемы.",
        date: "05/20/2018 14:05",
        images: []
      }
    ]
  }
};

export function generateData(newData) {
  let userData = [];
  const maxQuantityData = 15; // * 2

  for (let i = 0; i < maxQuantityData; i++) {
    userData = [...userData, ...newData];
  }

  return userData;
}

export function generateUserData(moduleName) {
  return new Promise((resolve, reject) => {
    timeout(() => {
      resolve();
    }, 250);
  }).then(() => {
    return generateData(testUserData[moduleName]);
  });
}

export function asSorter({ types, currentSorterType }) {
  return {
    types: types.reduce(
      (sorterTypes, type, index) => [
        ...sorterTypes,
        {
          id: index,
          name: type
        }
      ],
      []
    ),
    isListOpened: false,
    currentSorterType
  };
}
