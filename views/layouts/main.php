<?php
use app\models\League;
use app\models\News;
use app\models\Settings;
use app\widgets\AlertWidget;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\widgets\Pjax;
use app\widgets\WLang;

AppAsset::register($this);
?>



<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="referrer" content="never">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" href="/img/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="/img/favicon/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-touch-icon-72x72.png">
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-touch-icon-114x114.png">
    <link rel="preload" href="/js/vendor.js" as="script">
    <link rel="preload" href="/js/app.js" as="script">
    <link rel="preload" href="/css/app.css" as="style">
    <link rel="preload" href="/js/manifest.js" as="script">
    <?php $this->head() ?>
    <style>
        .curtain {
            position: fixed;
            background: #fff;
            top: 0;
            left: 0;
            display: flex;
            font-size: 2.5rem;
            justify-content: center;
            align-items: center;
            min-width: 100vw;
            min-height:  100vh;
            z-index: 1000;
            color: #333;
        }
    </style>
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="description" content="<?= Html::encode($this->description) ?>">

</head>

<body><noscript>This is your fallback content in case JavaScript fails to load.</noscript>
  <?php $this->beginBody() ?>
  <div class="layouts">
    <!-- begin header -->
    <?Pjax::begin(['id'=>'pj_header','options' => ['class'=>'parent v-centered']]);?>
    <header id="header" class="width_fill height_fill">

    <!-- begin curtain -->
    <transition name="fading">
    <div v-if="!isPageLoaded" class="curtain" role="presentation">
     <svg aria-live="assertive" role="alert" aria-label="Загрузка страницы" height="1em" width="1em" viewbox="0 0 44 44">
        <use xlink:href="#pagePreloader"/>
      </svg>
    </div>
    </transition>
    <!-- end curtain -->

    <!-- begin headerContainer -->
    <div class="headerContainer height_fill v-centered container parent row wrap h-between">
    <site-navigation class-name="baseChild" label="<?=Yii::t('main', 'Навигация по сайту')?>">
    </site-navigation>
    <!-- Для экранных читалок -->
    <div hidden="" id="currentPage">
    <?=Yii::t('main', 'Активная страница')?>
    </div>
    <!-- begin siteMeta -->
    <div class="siteMeta parent row nowrap h-between baseChild font-size_12 index_big">
      <div class="siteMeta__time color-paleGray relative">
        <script>
          window.timezones = <?=$this->params['timezones']?>;
        </script>

        <base-button id="openOrCloseTimezoneButton" :action="switchTimezonesListState" :label="`${isTimezonesShown ? 'Закрыть' : 'Открыть'} выбор временной зоны`"
          unstyled="">
          <time :datetime="accessibleTime" aria-label="<?=Yii::t('main', 'Тeкущее время') ?>" class="margin-right_quarter">
            [[ currentTime ]]
          </time>

          <chevron-icon :position="isTimezonesShown ? 'up' : 'down'" aria-labeledby="openOrCloseTimezoneButton">
          </chevron-icon>
        </base-button>

        <transition appear="" name="fadeTranslateToBottom">
          <ul :aria-expanded="isTimezonesShown" aria-label="Список временых зон" class="absolute font-size_18 popupList popupList_timezones"
            role="navigation" v-if="isTimezonesShown">
            <li v-for="(timezone, index) in window.timezones" :key="index">
              
              <base-button 
                :label="timezone.tz" 
                :action="chooseTimezone(timezone.tz)" 
                class-name="text_left"
                unstyled>
                [[ timezone.name ]]
              </base-button>
            </li>
          </ul>
        </transition>
      </div>
      <?= WLang::widget();?>
    </div>
    <!-- end siteMeta -->
    <transition name="fading">
    <!-- begin userPanel -->
    <?php if (!Yii::$app->user->isGuest): ?>
      <div  class="parent h-end row nowrap userPanel baseChild v-centered">
          <div class="userPanel__balance font-size_18 font-weight_semibold">
              &dollar; <?=Yii::$app->user->identity->balance?>
          </div>
          <button-icon :action="openPersonalRoom" :is-awesome-icon="false" class-name="userButton color_white" modifier="blue">
              <lazy-image :src="userIcon" class-name="userButton__icon" relative="">
              </lazy-image>
          </button-icon>
      </div>
    <?php else: ?>
      <div  class="parent row nowrap userPanel baseChild h-between v-centered font-size_10 margin-left_large h-end_tablet margin-left_zero-phone">
          <base-button :action="openAuthPopup('login')" class-name="margin-right_base-tablet" modifier="blue">
           <?=Yii::t('main', 'Войти')?>
          </base-button>
          <base-button :action="openAuthPopup('registration')" modifier="blue">
           <?=Yii::t('main', 'Регистрация')?>
          </base-button>
      </div>
    <?php endif; ?>

    <!-- end userPanel -->
    <!-- begin userPanel -->

    <!-- end userPanel -->
    </transition>

    </div>
    <user-authorization :submit-recover-password="() => {
    log(recoverPassword);

    const formName = formsNames.recoverPassword;
    const requestOptions = {
      url: '/send-email',
      data: {'email':recoverPassword.email, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>',},
      method: 'POST',
    };

    $store.commit('authorization/switchRequestingState', true);

   const request = axios(requestOptions);



    request.then(response => {
    const isError =  response.data !== 'ok';

      const message = response.message;
      const responseOptions = {
        message: isError ? '<?=Yii::t('main', 'Мы не нашли аккаунт с таким email адресом.')?>' : `<?=Yii::t('main', 'Мы выслали письмо с инструкциями по восстановлению пароля на вашу почту ${recoverPassword.email}.')?>`,
        isError,
        formName
      };

      $store.dispatch(
        'authorization/showResponseMessage',
        responseOptions
      );
    });
    }" :submit-login="() => {
    log(login);

    const formName = formsNames.login;
    if (!login.username && !login.password) {
    return false;
    };

    const requestOptions = {
    url: '/login',
    data: { login:login.username, password:login.password, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>',},
    method: 'POST',

    };

    //$store.commit('authorization/switchRequestingState', true);

     const request = axios(requestOptions);


    request.then(response => {

      console.log(response);

     const isError = response.data !== 'ok';




      const responseOptions = {
        errorMessage: isError ? '<?=Yii::t('main', 'Неверный логин или пароль.')?>' : '',
        isError,
        formName
      };
      if(!isError){
        $.pjax.reload({container : '#pj_header'});
      }

      $store.dispatch(
        'authorization/showUserDataIfUserLogged',
        responseOptions
      );

    });

    }" :submit-registration="() => {
    log(registration);

    const formName = formsNames.registration;



    const requestOptions = {
      url: '/signup',
      data: { 'username':registration.username, 'password':registration.password, 'email':registration.email, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>',},
      method: 'POST',
    };


    if (registration.repeatedPassword !== registration.password) {
      const isError = registration.repeatedPassword !== registration.password;
      const responseOptions = {
        userData: {
          balance: 0,
          name: registration.username,
          avatar: 'None',
          id: 139452
        },
        errorMessage:  '<?=Yii::t('main', 'Пароли не совпадают.')?>' ,
        successMessage: '',
        isError,
        formName,
      };
      $store.dispatch(
        'authorization/notifyAboutSuccesRegistration',
        responseOptions
      );
    return false;
    };

    $store.commit('authorization/switchRequestingState', true);

    const request = axios(requestOptions);



    request.then(response => {
      const isError = response.data !== 'ok';
      const responseOptions = {
        userData: {
          balance: 0,
          name: registration.username,
          avatar: 'None',
          id: 139452
        },
        errorMessage: isError ? response.data : '',
        successMessage: '<?=Yii::t('main', 'Вы успешно зарегистрировались!')?>',
        isError,
        formName,
      };
      if(!isError){
        $.pjax.reload({container : '#pj_header'});
      }

      this.$store.dispatch(
        'authorization/notifyAboutSuccesRegistration',
        responseOptions
      );
    });



    }">
    </user-authorization>
    </header>
    <!-- end header -->
    <?php if (Yii::$app->request->get('_pjax')): ?>
      <script type="text/javascript">

        window.ComponentsHandler.initSection('header');

      </script>
    <?php endif; ?>

    <?Pjax::end();?>

<?= $content ?>
<footer id="footer" class="background-color_blueGradient footer color_white">
    <!-- begin container -->
    <div class="container relative endlessBottomBorder_white endlessBottomBorder padding-bottom_base padding-bottom_large-phone padding-top_small parent row wrap v-centered">
        <!-- begin site-navigation -->
        <site-navigation :logo-colored="false" active-link-color="white" label="Навигация по сайту в нижней части страницы" logo-color-name="white" modifier="footer" navigation-style="white">
        </site-navigation>
        <!-- end site-navigation -->

        <!-- begin subscribtionForm -->
        <!-- <subscribtion-form></subscribtion-form> -->
        <form class="font-size_12 subscribtionForm parent row nowrap grow h-between margin-top_base-phone margin-bottom_base-phone">
            <label class="subscribtionForm__label parent centered margin-left_auto width_fill-phone" for="subscribtion_email">
              <?=Yii::t('main', 'Подписаться на рассылку:')?>
            </label>
            <!-- begin controller -->
            <div class="relative controller parent row nowrap grow margin_centered-phone margin-top_small-phone">
                <input id="subscribtion_email" v-model.trim="subscribtion_email" class="round_25 controller__input" type="email" name="subscribtion_email" placeholder="Введите ваш e-mail" autocomplete="email">
                <base-button :action="onSubmit" type="submit" modifier="blue" class-name="text_upper absolute position_right-base controller__button">
                    <?=Yii::t('main', 'Отправить')?>
                </base-button>
            </div>
            <!-- end controller -->
        </form>
        <!-- end subscribtionForm -->
    </div>
    <!-- end container -->

    <!-- begin container -->
    <div class="padding-top_base container parent row wrap v-centered">
        <p class="copyright opacity_half grow text_centered-phone margin-top_zero-phone">
            <?=Yii::t('main', '©2017. Все права защищены')?>
        </p>
        <footer-games>
        </footer-games>
        <!-- end  games-->
    </div>
    <!-- end container -->
    <script>
      setInterval(function() { 
      betsSorter = $store.state.sorters.betSorter;
      betsSorter.page = $('#load_more').data('page');
        $.pjax.reload({method: 'POST',
          container:'#all_matches', 
          data : betsSorter,
          push: false,
          timeout: 3000,
          replace: false
        });

      }, 2000);
    </script>
</footer>
<!-- end footer -->

        </div>

<?=$this->render('personal-room')?>


<div class="display_contents" id="matchPopup" v-if="$store.state.popups[matchPopupName]">
  <modal-container :is-shown="$store.state.popups[matchPopupName]" :is-data-updated="$store.getters['popups/isMatchDataUpdated']" :on-close-modal="closeMatchPopup" :on-open="() => {

    const requstMethod = 'GET';
    const requestUrl = $store.state.bets.choosenMatchUrl;

    const requestOptions = {
      url: requestUrl,
      data: {},
      method: requstMethod,
    };

    // https://github.com/axios/axios
    const request = axios(requestOptions);



      request.then((response) => {
        log(response);
        const generatedHtml = response.data;

        renderStaticTemplate(generatedHtml);

        function renderStaticTemplate(html) {
          const matchPopup = $store.state.document.getElementById('matchPopup');

          matchPopup.innerHTML = html;

          setInitedStateOfPopup();
          rerenderMatchPopup();
        }
      }); // end request
      }" class-name="modalMatchContainer">

  </modal-container>
</div>


<modal-container id="notificationPopup" v-if="$store.state.popups[noftificationPopupName]" :on-close-modal="closeNotificationPopup" :on-open="() => {
      log(requestStatus, 'on-open');
  }" class-name="modalMatchContainer" is-big-close-button>
      <transition name="fadeTranslateToTop">
        <!-- start successBlock -->
        <div v-if="requestStatus.message" :class="{
            'successBlock formContainer': true,
            'color_blue': requestStatus.isSuccess,
            'color_red': requestStatus.isError
          }" aria-live="assertive" role="alert" aria-labeledby="popupNotificationDescription">
          <div id="popupNotificationDescription" aria-hidden="true" hidden><?=Yii::t('main', 'Сообщение для пользователя')?></div>
          <p v-html="requestStatus.message">
          <!-- begin authoriaztionButtons -->
          <?php if (Yii::$app->user->isGuest): ?>

          <div v-if="requestStatus.isError && requestStatus.isAuthorizationBlock" class="parent row nowrap margin-top_increased font-size_12 centered">
              <base-button :action="openAuthPopup('login')" class-name="margin-right_base" modifier="blue">
               <?=Yii::t('main', 'Войти')?>
              </base-button>
              <base-button :action="openAuthPopup('registration')" modifier="blue">
               <?=Yii::t('main', 'Регистрация')?>
              </base-button>
          </div>

          <?php else : ?>
          <div v-if="requestStatus.isActionsBlock" class="parent row nowrap margin-top_increased font-size_12 centered">
              <base-button :action="() => {
                    log(newUserBet);
                    const requestOptions = {
                       method: 'post',
                       url: '/pay-order',
                       data: {'order_id' :  newUserBet.order_id, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                    };
                    const request = axios(requestOptions);
                    window.setTimeout(() => {
                      request.then(response => {
                      openNotificationPopup();
                        const isError = response.data.error != 0;
                        const message = response.data.message;
                        const responseOptions = {
                          message,
                          isError,
                          isAuthorizationBlock: false,
                        };

                        $store.dispatch(
                          'authorization/showResponseMessage',
                          responseOptions
                        );
                        if(!isError)
                        {
                          $.pjax.reload({
                          url: response.data.url,
                          container:'#modal_match', 
                        
                          push: false,
                          timeout: 3000,
                          replace: false
                        }); 
                          $store.commit('personalRoom/setUserBalance', response.data.balance);
                        }
                        
                        return false;
                      });
                    }, 500);
                  }" class-name="margin-right_base" modifier="green">
               <?=Yii::t('main', 'Оплатить')?>
              </base-button>
              <base-button :action="closeNotificationPopup" modifier="blue">
               <?=Yii::t('main', 'Отложить')?>
              </base-button>
          </div>

          <?php endif ?>
        </div>
        <!-- end meta -->

    </transition>
    <!-- end successBlock -->
</modal-container>


        <svg aria-hidden="true" role="img" hidden="">
            <g id="pagePreloader" stroke="currentColor" fill="none" fill-rule="evenodd" stroke-width="2">
                <circle cx="22" cy="22" r="1">
                    <animate attributename="r" begin="0s" calcmode="spline" dur="1.8s" keysplines="0.165, 0.84, 0.44, 1" keytimes="0; 1" repeatcount="indefinite" values="1; 20">
                    </animate>
                    <animate attributename="stroke-opacity" begin="0s" calcmode="spline" dur="1.8s" keysplines="0.3, 0.61, 0.355, 1" keytimes="0; 1" repeatcount="indefinite" values="1; 0">
                    </animate>
                </circle>
                <circle cx="22" cy="22" r="1">
                    <animate attributename="r" begin="-0.9s" calcmode="spline" dur="1.8s" keysplines="0.165, 0.84, 0.44, 1" keytimes="0; 1" repeatcount="indefinite" values="1; 20">
                    </animate>
                    <animate attributename="stroke-opacity" begin="-0.9s" calcmode="spline" dur="1.8s" keysplines="0.3, 0.61, 0.355, 1" keytimes="0; 1" repeatcount="indefinite" values="1; 0">
                    </animate>
                </circle>
            </g>
        </svg>

        <script>!function(){"use strict";var o=Boolean("localhost"===window.location.hostname||"[::1]"===window.location.hostname||window.location.hostname.match(/^127(?:\.(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)){3}$/));window.addEventListener("load",function(){"serviceWorker"in navigator&&("https:"===window.location.protocol||o)&&navigator.serviceWorker.register("service-worker.js").then(function(o){o.onupdatefound=function(){if(navigator.serviceWorker.controller){var n=o.installing;n.onstatechange=function(){switch(n.state){case"installed":break;case"redundant":throw new Error("The installing service worker became redundant.")}}}}}).catch(function(o){console.error("Error during service worker registration:",o)})})}();</script>
        <!-- built files will be auto injected -->

<?php



?>
<?php $this->endBody() ?>
<script>
  $(document).on('pjax:error', function(event, xhr, textStatus, errorThrown, options) {
    options.success(xhr.responseText, textStatus, xhr);
    return false;
});
</script>
    </body>
    </html>
<?php $this->endPage() ?>
