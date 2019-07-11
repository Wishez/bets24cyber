export const transformDate = (date, format = "LL") => {
  return moment(date)
    .locale("ru")
    .format(format);
};
// There is the Async ClipboardAPI.
export const selectText = element => {
  var doc = document,
    text = element,
    range,
    selection;

  if (doc.body.createTextRange) {
    range = document.body.createTextRange();
    range.moveToElementText(text);
    range.select();
  } else if (window.getSelection) {
    selection = window.getSelection();
    range = document.createRange();
    range.selectNodeContents(text);
    selection.removeAllRanges();
    selection.addRange(range);
  }
};

// https://stackoverflow.com/questions/1527803/generating-random-whole-numbers-in-javascript-in-a-specific-range
export const getRandomId = (min, max) => {
  return Math.floor(Math.random() * (max - min + 1)) + min;
};
export const listen = ({
  element = window,
  callback,
  event = "DOMContentLoaded"
}) => {
  element.addEventListener(event, callback);
};

export const doByYScroll = ({
  target,
  offsetY = 0,
  condition = true,
  onTrigger,
  direction = "bottom"
}) => {
  const currentScrollPosition = target.scrollTop;

  if (condition) {
    switch (direction) {
      case "bottom":
        if (currentScrollPosition > offsetY) {
          onTrigger();
        }

        break;
      case "top":
        if (currentScrollPosition < offsetY) {
          onTrigger();
        }

        break;
      default:
        throw new Error(
          'You can use one of these directions: "top" or "bottom".'
        );
    }
  }
};

export function doBy({
  callback,
  condition = window.innerWidth > 768,
  fallback = false
}) {
  if (condition) {
    callback();
  } else {
    if (fallback) fallback();
  }
}
export function transformName(name) {
  return `${name.charAt(0).toUpperCase()}${name.slice(1).toLowerCase()}`;
}

export const throttle = callback => {
  let isRunning = false;

  return event => {
    if (isRunning) return;

    isRunning = true;

    window.requestAnimationFrame(() => {
      callback(event);
      isRunning = false;
    });
  };
};
// https://gist.github.com/gordonbrander/2230317
export const ID = function() {
  // Math.random should be unique because of its seeding algorithm.
  // Convert it to base 36 (numbers + letters), and grab the first 9 characters
  // after the decimal.
  return (
    "_" +
    Math.random()
      .toString(36)
      .substr(2, 9)
  );
};
export const slideTo = ({ selector = false, element = false, offset = 0 }) => {
  if (selector) {
    element = document.querySelector(selector);
  }

  if (element) {
    element.scrollIntoView({
      behavior: "smooth",
      block: "start"
    });
  }
  if (offset) {
    document.documentElement.scrollTop -= offset;
  }
};

export function timeout(callback, timeout) {
  // stuff for animating goes here
  let pastTime = 0;
  function animate(time) {
    if (!pastTime) {
      pastTime = time;
    }
    const delta = time - pastTime;

    if (delta >= timeout) {
      callback();
      return false;
    }

    requestAnimationFrame(animate);
  }

  animate();
}

export const timer = (callback, delay = 1000) => {
  let timeout = null;

  return () => {
    if (timeout) {
      clearTimeout(timeout);
    }

    timeout = setTimeout(() => {
      callback();
    }, delay);
  };
};

export const convertDate = date => {
  return new Date(date).toLocaleDateString("ru-RU", {
    hour: "numeric",
    minute: "numeric",
    second: "numeric"
  });
};

export const prevent = event => {
  event.preventDefault();
  return false;
};
export const notFollow = event => {
  prevent(event);

  const url = event.target.href;

  window.open(url);
};

export function getArray(object) {
  let newArray = [];
  for (const prop in object) {
    newArray.push(object[prop]);
  }
  return newArray;
}

export function timeoutify({ callback, delay = 500, message = "Timeout!" }) {
  let intv = setTimeout(function() {
    intv = null;
    callback(new Error(message));
  }, delay);

  return function() {
    if (intv) {
      clearTimeout(intv);

      callback.apply(this, [null].concat([].slice.call(arguments)));
    }
  };
}
