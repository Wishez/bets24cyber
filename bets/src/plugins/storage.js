window.$storage = {
  get(item) {
    return JSON.parse(localStorage[item]);
  },
  set(item, value) {
    localStorage[item] = JSON.stringify(value);
  }
};
