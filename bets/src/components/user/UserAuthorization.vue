<template>
  <modal-container 
    v-if="$store.state.popups.authorization"
    id="userAuthorization" 
    :on-close-modal="closeAuthPopup"
    class-name="userAuthorizationPopup"
    container-classes="index_big  "
    modifier="small"
    close-button-modifier="normal"
    small-litter
    is-litter-fit-content
  >
    <slot/>

    <transition name="fadeTranslateToTop">
      <div v-if="login.isShown">
        <form 
          v-if="!requestStatus.isSuccess"
          action="" 
          class="logInForm formContainer parent column width_fill font-size_12"
          aria-label="Форма входа в аккаунт пользователя"
          @submit.prevent="submitLogin"
        >
          <label 
            class="logInForm__label inputLabel" 
            for="currentUsernameInput">
            Имя пользователя
          </label>
          <input 
            id="currentUsernameInput" 
            v-model="login.username"
            type="text" 
            class="logInForm__input input"
            name="loginUsername" 
            placeholder="Daniel_Kaneman"
            autocomplete="username" 
          >
          <!-- begin passwordBlock -->
          <div class="passwordBlock relative">
            <label 
              class="logInForm__label inputLabel" 
              for="threadThemeInput">
              Пароль
          
            </label>
            <input 
              id="newUserPasswordInput" 
              v-model="login.password"
              :type="!isShownLoginPassword ? 'password' : 'text'"
          
              class="logInForm__input input"
              name="loginPassword" 
              placeholder="My_Current_Password"
              autocomplete="current-password"
            >
            <show-password-button 
              @change-shown-state="isShownLoginPassword = $event;"
            />
          </div>
          <transition 
            name="fading" 
          >
            <p 
              v-if="requestStatus.isError && requestStatus.message"
              class="color_red margin-bottom_base margin-top_zero font-size_18"> 
              {{ requestStatus.message }}
            </p>
          </transition>

          <!-- begin logInFormSubmit -->
          <div class="parent h-between relative width_fill loginFormSubmit">
            <div class="parent row grow">
              <base-button 
                :action="switchForm(formsNames.recoverPassword)"
                class-name="color_paleBlue margin-right_base" 
                unstyled>
                Забыли пароль?
              </base-button>
              <base-button 
                :action="switchForm(formsNames.registration)"
                class-name="color_paleBlue" 
                unstyled>
                Регистрация
              </base-button>
            </div>
          
          
            <base-button 
            
              type="submit"
              modifier="blue"
              class-name="loginFormSubmit__button formButton">
              {{ requestStatus.isRequesting ? 'Входим...' : 'Войти' }}
            </base-button>
            <transition 
              name="fadeTranslateToTop" 
              appear>
              <p 
                v-if="login.hint"
                class="absolute position_right position-bottom_full-offset color_red padding_small-container background-color_white round_slight shadow_litter font-size_18 newThreadFormSubmit__hint">
                [[ login.hint ]]
              </p>
            </transition>
            <!-- end userHint -->
          </div>
          <!-- end logInFormSubmit -->
        </form>
        <div 
          v-else 
          class="successBlock formContainer"
        >
          <h2 class="headingOffset">Вход</h2>
          <p 
            v-html="requestStatus.message"
          /> 
        </div>

      </div>
      <!-- end singInForm -->

      <!-- begin singInForm -->
      <div v-else-if="registration.isShown">
        <form 
          v-if="!requestStatus.isSuccess"
          action="" 
          class="singInForm parent column width_fill font-size_12 formContainer"
          aria-label="Форма регистрации пользователя"
          @submit.prevent="submitRegistration"
        >
          <label 
            class="loginFormSubmit__label inputLabel" 
            for="newUserUsername">
            Имя пользователя
          </label>
          <input 
            id="newUserUsername" 
            v-model="registration.username"
            type="text" 
            class="loginFormSubmit__input input"
            name="newUserUsername" 
            placeholder="Aria_Stark"
            autocomplete="username" 
          >
          <label 
            class="loginFormSubmit__label inputLabel" 
            for="newUserEmail"
          >
            Email
          </label>
          <input 
            id="newUserEmail" 
            v-model="registration.email"
            type="email" 
            class="loginFormSubmit__input input"
            name="newUserEmail" 
            autocomplete="email" 
            placeholder="vengefulGirl@mail.ru"
          >
          <!-- begin passwordBlock -->
          <div class="passwordBlock relative">
            <label 
              class="loginFormSubmit__label inputLabel" 
              for="newUserPassword">
              Пароль
            </label>

            <input 
              id="newUserPassword" 
              :type="!isShownRegistrationPassword ? 'password' : 'text'"
              v-model="registration.password"
              class="loginFormSubmit__input input"
              name="newUserPassword" 
              placeholder="My_Secret_Password"
              autocomplete="new-password" 
            >
            <show-password-button 
              @change-shown-state="isShownRegistrationPassword = $event"
            />
          </div>
          <!-- end passwordBlock -->

          <!-- begin passwordBlock -->
          <div class="passwordBlock relative">
      	
            <label 
              class="loginFormSubmit__label inputLabel" 
              for="newUserRepeatedPassword">
              Проверка пароля
            </label>
            <input 
              id="newUserRepeatedPassword" 
              :type="!isShownRegistrationPassword ? 'password' : 'text'"
              v-model="registration.repeatedPassword"
              class="loginFormSubmit__input input"
              name="newUserRepeatedPassword" 
              placeholder="My_Secret_Password" 
              autocomplete="new-password"
            >
            <show-password-button 
              @change-shown-state="isShownRegistrationPassword = $event"
            />
          </div>
          <!-- end passwordBlock -->
          <transition 
            name="fading" 
          >
            <p 
              v-if="requestStatus.isError && requestStatus.message"
              class="color_red margin-bottom_base margin-top_zero font-size_18"> 
              {{ requestStatus.message }}
            </p>
          </transition>

          <!-- begin singInFormSubmit -->
          <div class="parent h-between relative width_fill loginFormSubmit">
            <base-button 
              :action="switchForm(formsNames.login)"
              class-name="color_paleBlue" 
              unstyled>
              Войти
            </base-button>
            <base-button 
            
              type="submit"
              modifier="blue"
              class-name="loginFormSubmit__button formButton">
              {{ requestStatus.isRequesting ? 'Регистрируем...' : 'Зарегистрироваться' }}
            </base-button>
            <transition 
              name="fadeTranslateToTop" 
              appear>
              <p 
                v-if="registration.hint"
                class="absolute position_right position-bottom_full-offset color_red padding_small-container background-color_white round_slight shadow_litter font-size_18 newThreadFormSubmit__hint">
                [[ registration.hint ]]
              </p>
            </transition>
            <!-- end userHint -->
          </div>
          <!-- end singInForm -->
        </form>
        <!-- begin successBlock -->
        <div 
          v-else 
          class="successBlock formContainer"
        >
          <h2 class="headingOffset">Регистрация</h2>
          <p
            v-html="requestStatus.message"
          /> 
        </div>
        <!-- end successBlock -->
      </div>

      <!-- begin recoverPasswordForm  -->
      <div v-else-if="recoverPassword.isShown">        
        <form 
          v-if="!requestStatus.isSuccess"
          action="" 
          class="recoverPasswordForm formContainer parent column width_fill font-size_12"
          aria-label="Форма восстановления пароля от аккаунта пользователя"
          @submit.prevent="submitRecoverPassword"
        >
          <label 
            class="recoverPasswordForm__label inputLabel" 
            for="recoverUserEmailInput">
            Email
          </label>
          <input 
            id="recoverUserEmailInput" 
            v-model="recoverPassword.email"
            type="email" 
            class="recoverPasswordForm__input input"
            name="recoverEmail" 
            placeholder="recoverPassword@mail.ru"
            autocomplete="email" 
          >
          <transition 
            name="fading" 
          >
            <p 
              v-if="requestStatus.isError && requestStatus.message"
              class="color_red margin-bottom_base margin-top_zero font-size_18"
            > 
              {{ requestStatus.message }}
            </p>
          </transition>
          <!-- begin recoverPasswordFormSubmit -->
          <div class="parent h-between relative width_fill recoverPasswordFormSubmit">
            <div class="parent row grow">
              <base-button 
                :action="switchForm(formsNames.login)"
                class-name="color_paleBlue margin-right_base" 
                unstyled>
                Вход
              </base-button>
              <base-button 
                :action="switchForm(formsNames.registration)"
                class-name="color_paleBlue" 
                unstyled>
                Регистрация
              </base-button>
            </div>
            <base-button 
            
              type="submit"
              modifier="blue"
              class-name="recoverPasswordFormSubmit__button formButton">
              {{ requestStatus.isRequesting ? 'Отсылаем сообщение...' : 'Восстановить' }}
            </base-button>

            <transition 
              name="fadeTranslateToTop" 
              appear>
              <p 
                v-if="recoverPassword.hint"
                class="absolute position_right position-bottom_full-offset color_red padding_small-container background-color_white round_slight shadow_litter font-size_18 newThreadFormSubmit__hint">
                [[ recoverPassword.hint ]]
              </p>
            </transition>
            <!-- end userHint -->
          </div>
          <!-- end recoverPasswordFormSubmit -->
        </form>
        <!-- begin successBlock -->
        <div 
          v-else 
          class="successBlock formContainer"
        >
          <h2 class="headingOffset">Восстановление пароля</h2>
          <p
            v-html="requestStatus.message"
          /> 
        </div>
        <!-- end successBlock -->
      </div>
    <!-- end recoverPasswordForm -->
    </transition>
  </modal-container>
</template>

<script>
import popupsNames from "@/constants/popups";
import formsNames from "@/constants/authorization";
import ShowPasswordButton from "@/components/buttons/ShowPasswordButton";

export default {
  name: "UserAuthorization",
  delimiters: ["[[", "]]"],
  components: {
    ShowPasswordButton
  },

  props: {
    submitRecoverPassword: {
      type: Function,
      required: true
    },
    submitRegistration: {
      type: Function,
      required: true
    },
    submitLogin: {
      type: Function,
      required: true
    }
  },
  data: () => ({
    isShownRegistrationPassword: false,
    isShownLoginPassword: false,
    formsNames
  }),
  computed: {
    authorization() {
      return this.$store.state.authorization;
    },

    login() {
      return this.authorization.login;
    },

    registration() {
      return this.authorization.registration;
    },

    recoverPassword() {
      return this.authorization.recoverPassword;
    },

    requestStatus() {
      return this.authorization.requestStatus;
    }
  },
  mounted() {},
  methods: {
    switchForm(formName) {
      return () => {
        this.$store.dispatch("authorization/switchForm", formName);
      };
    },

    log(message) {
      console.log(message);
    },

    closeAuthPopup() {
      this.$store.commit("popups/switchPopupState", {
        popupName: popupsNames.authorization,
        isOpened: false
      });

      this.$nextTick(() => {
        this.$store.commit("authorization/cleanRequestState");
      });
    }
  }
};
</script>

<style lang="sass" scoped>
#userAuthorization
  z-index: 1001
.userAuthorizationPopup
	min-heigh: 152px
.passwordBlock
	display: flex
	flex-direction: column
	
</style>
