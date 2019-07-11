import { requestUrls } from "@/constants/conf";
import { prevent, timeout } from "@/constants/pureFunctions";

import SubscribtionForm from "@/components/SubscribtionForm";

export default {
  el: "#footer",
  components: {
    SubscribtionForm
  },
  data: () => ({
    subscribtion_email: "",
    response: {
      serverMessage: "",
      success: false,
      error: false,
      requestion: false
    }
  }),
  methods: {
    setResponseData(data) {
      this.$set(this, "response", data);
    },
    onSubmit(event) {
      // Ссылка на объект состояния формы.
      const oldResponseData = this.response;
      // Извлекаем данные. При необходимости,
      // ключ (user_email) по которому будет присваиваться
      // подписка можно поменять.
      const data = {
        user_email: this.subscribtion_email
      };

      // POST запрос на подписку.
      axios({
        method: "post",
        url: requestUrls.subscribtion,
        // Заботимся об безопасности.
        headers: {
          "X-CSRFToken": this.$store.state.csrftoken
        },
        data
      })
        .then(response => {
          // Успешно отправляем сообщение с сервера в отображение.
          this.setResponseData({
            ...oldResponseData,
            serverMessage: response.data,
            requesting: false,
            success: true
          });
        })
        .catch(error => {
          // Сообщение об ошибке.
          this.setResponseData({
            ...oldResponseData,
            serverMessage: `Внутренняя ошибка сервера: ${error}`,
            requesting: false,
            error: true
          });
          // Таймаут, по истечению которого сообщение об ошибки
          // скрывается.
          timeout(() => {
            this.setResponseData({
              ...oldResponseData,
              error: false,
              serverMessage: ""
            });
          }, 3000);
        });
      prevent(event);
    }
  }
};
