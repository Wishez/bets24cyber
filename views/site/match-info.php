<?php

use app\models\Bookmaker;
use app\models\League;
use app\models\Team;
use kartik\date\DatePicker;
use kartik\depdrop\DepDrop;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Match;
// use yii\db\Query;
use yii\widgets\Pjax;

$this->params['class_page'] = 'match-info';

$this->title = 'Расписание матчей по dota 2 и cs go';
$this->description = '24cyber предоставляет всю информацию о прошедших , текущих и будущих матчах по дота 2 и кс го: стримы , онлайн статистика, коэффициенты , история встреч.';


$this->params['breadcrumbs'][] = $this->title;
?>
<?Pjax::begin(['id'=>'modal_match','options' => ['class'=>'']]);?>
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
<transition name="fading">
  <article class="popupMatch">
      <bets-match-popup-header league-name="<?=$match->league->name?>" league-stage=" " match-date="<?=Yii::$app->formatter->asDate($match->date, 'php:m/d/Y H:i');?>">

          <match-teams
              :last-ratio-values="{
                  left: <?=$match->teamOneRatioLast?>,
                  right: <?=$match->teamTwoRatioLast?>
              }"
              :is-completed="false"
              :match-score="{
                left: <?=$match->score_left?>,
                  right: <?=$match->score_right?>
              }"
              
              left-team-ratio="<?=$match->teamOneRatio?>"
              left-team-name="<?=$match->teamFirst->name?>"
              left-team-logo="https://24cyber.ru<?=$match->teamFirst->logo?>"
              left-team-country="https://24cyber.ru<?=$match->teamFirst->fl?>"
              
              right-team-ratio="<?=$match->teamTwoRatio?>"
              right-team-name="<?=$match->teamTwo->name?>"
              right-team-logo="https://24cyber.ru<?=$match->teamTwo->logo?>"
              
              right-team-country="https://24cyber.ru<?=$match->teamTwo->fl?>"
    
              series="Best of <?=$match->series?>"
              
              match-url="<?=Url::to(['site/match-details', 'id'=>$match->id])?>"
              class-name="popupMatchTeams padding-bottom_base"
              team-name-class="text_centered-phone-force" 
              team-logo-container-class="display_grid popupMatchLogos" 
              team-logo-class="max-width_40 max-height_40" 
              ratio-container-class="max-width_28"
               versus-class="popupTeamsVersus" 
               left-team-name-class="margin-top_small-tabletLess " 
               right-team-class="popupRightTeam popupTeam translate-left_small-xsUp" 
               left-team-class="popupLeftTeam popupTeam" 
               names-beneath-logo 
               is-big-versus 
               is-small-ratio></match-teams>

          <match-streams :channels="<?= $streams_js ?>" class-name="popupMatchStreams"></match-streams>
      </bets-match-popup-header>

    <!-- Декоративные метки -->
    <bets-match-popup-labels></bets-match-popup-labels>
    <!-- Конец декоративным меткам -->

    <!-- begin matchStatus -->
    <div class="matchStatus background-color_white">
            <!-- Заголовки таблицы -->
            <bets-match-popup-bets-header></bets-match-popup-bets-header>
            <!-- Конец заголовкам -->
            <team-status-bar team-name="<?=$match->teamFirst->name?>" team-status="Победа" team-logo="https://24cyber.ru<?=$match->teamFirst->logo?>" :bets="<?=$j_orders_first?> ? <?=$j_orders_first?> : []" @accept-user-bet="(acceptBetValues) => {
                log(acceptBetValues);

                const formName = matchNoftificationPopupName;
                const requestOptions = {
                   method: 'post',
                   url: '/repay-order',
                   data: {'order_id' : acceptBetValues.bet.id, 'summ' : acceptBetValues.accepted.bet , 'team_id' : 1 , 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                };

                const request = axios(requestOptions);

                window.setTimeout(() => {
                  request.then(response => {
                    log(response);
                    if(response.data.order_id){
                      acceptBetValues.order_id = response.data.order_id;
                    }

                    openNotificationPopup();
                      const isError = response.data.error != 0;
                      const message = response.data.message;
                      const responseOptions = {
                        message,
                        isError,
                        isAuthorizationBlock: response.data.error == 2,
                      };

                      $store.dispatch(
                        'authorization/showResponseMessage',
                        responseOptions
                      );
                      if(!isError){
                        $store.commit('personalRoom/setUserBalance', response.data.balance);
                      }
                    });
                }, 500);
              }" @offer-new-bet="(acceptBetValues) => {
                log(acceptBetValues);



                const formName = matchNoftificationPopupName;
                const requestOptions = {
                   method: 'post',
                   url: '/add-order',
                   data: {'author_id' : '<?=Yii::$app->user->id?>','match_id' : '<?=$match->id?>', 'summ' : acceptBetValues.bet , 'rate' : acceptBetValues.ratio, 'winner' : 1 , 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                };

                const request = axios(requestOptions);


                window.setTimeout(() => {
                  request.then(response => {
                  log(response);
                  if(response.data.order_id){
                    acceptBetValues.order_id = response.data.order_id;
                  }

                  openNotificationPopup();
                    const isError = response.data.error != 0;
                    const message = response.data.message;
                    const responseOptions = {
                      message,
                      isError,
                      isAuthorizationBlock: response.data.error == 2,
                      isActionsBlock: response.data.error == 0,
                    };

                    $store.dispatch(
                      'authorization/showResponseMessage',
                      responseOptions
                    );
                  });
                }, 500);
              }"></team-status-bar>
            <team-status-bar team-name="<?=$match->teamTwo->name?>" team-status="Победа" team-logo="https://24cyber.ru<?=$match->teamTwo->logo?>" :bets="<?=$j_orders_two?> ? <?=$j_orders_two?>:[]" @accept-user-bet="(acceptBetValues) => {
                log(acceptBetValues);

                const formName = matchNoftificationPopupName;
                const requestOptions = {
                   method: 'post',
                   url: '/repay-order',
                   data: {'order_id' : acceptBetValues.bet.id, 'summ' : acceptBetValues.accepted.bet , 'team_id' : 2 , 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                };

                const request = axios(requestOptions);

                window.setTimeout(() => {
                  request.then(response => {
                    log(response);
                    if(response.data.order_id){
                      acceptBetValues.order_id = response.data.order_id;
                    }

                    openNotificationPopup();
                      const isError = response.data.error != 0;
                      const message = response.data.message;
                      const responseOptions = {
                        message,
                        isError,
                        isAuthorizationBlock: response.data.error == 2,
                      };

                      $store.dispatch(
                        'authorization/showResponseMessage',
                        responseOptions
                      );
                      if(!isError){
                        $store.commit('personalRoom/setUserBalance', response.data.balance);
                      }
                    });
                }, 500);
              }" @offer-new-bet="(acceptBetValues) => {
                log(acceptBetValues);



                const formName = matchNoftificationPopupName;
                const requestOptions = {
                   method: 'post',
                   url: '/add-order',
                   data: {'author_id' : '<?=Yii::$app->user->id?>','match_id' : '<?=$match->id?>', 'summ' : acceptBetValues.bet , 'rate' : acceptBetValues.ratio, 'winner' : 1 , 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                };

                const request = axios(requestOptions);


                window.setTimeout(() => {
                  request.then(response => {
                  log(response);
                  if(response.data.order_id){
                    acceptBetValues.order_id = response.data.order_id;
                  }

                  openNotificationPopup();
                    const isError = response.data.error != 0;
                    const message = response.data.message;
                    const responseOptions = {
                      message,
                      isError,
                      isAuthorizationBlock: response.data.error == 2,
                      isActionsBlock: response.data.error == 0,
                    };

                    $store.dispatch(
                      'authorization/showResponseMessage',
                      responseOptions
                    );
                  });
                }, 500);
              }"></team-status-bar>
              <?php if ($match->series==2): ?>
                <team-status-bar :team-name="''" team-status="<?=Yii::t('main', 'Ничья')?>" :team-logo="''" :bets="<?=$j_orders_two?> ? <?=$j_orders_draw?>:[]" @accept-user-bet="(acceptBetValues) => {
                log(acceptBetValues);

                const formName = matchNoftificationPopupName;
                const requestOptions = {
                   method: 'post',
                   url: '/repay-order',
                   data: {'order_id' : acceptBetValues.bet.id, 'summ' : acceptBetValues.accepted.bet , 'team_id' : 0 , 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                };

                const request = axios(requestOptions);

                window.setTimeout(() => {
                  request.then(response => {
                    log(response);
                    if(response.data.order_id){
                      acceptBetValues.order_id = response.data.order_id;
                    }

                    openNotificationPopup();
                      const isError = response.data.error != 0;
                      const message = response.data.message;
                      const responseOptions = {
                        message,
                        isError,
                        isAuthorizationBlock: response.data.error == 2,
                      };

                      $store.dispatch(
                        'authorization/showResponseMessage',
                        responseOptions
                      );
                      if(!isError){
                        $store.commit('personalRoom/setUserBalance', response.data.balance);
                      }
                    });
                }, 500);
              }" @offer-new-bet="(acceptBetValues) => {
                log(acceptBetValues);



                const formName = matchNoftificationPopupName;
                const requestOptions = {
                   method: 'post',
                   url: '/add-order',
                   data: {'author_id' : '<?=Yii::$app->user->id?>','match_id' : '<?=$match->id?>', 'summ' : acceptBetValues.bet , 'rate' : acceptBetValues.ratio, 'winner' : 0 , 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
                };

                const request = axios(requestOptions);


                window.setTimeout(() => {
                  request.then(response => {
                  log(response);
                  if(response.data.order_id){
                    acceptBetValues.order_id = response.data.order_id;
                  }

                  openNotificationPopup();
                    const isError = response.data.error != 0;
                    const message = response.data.message;
                    const responseOptions = {
                      message,
                      isError,
                      isAuthorizationBlock: response.data.error == 2,
                      isActionsBlock: response.data.error == 0,
                    };

                    $store.dispatch(
                      'authorization/showResponseMessage',
                      responseOptions
                    );
                  });
                }, 500);
              }"></team-status-bar>
              <?php endif ?>
            
            
        </div>
    <!-- end matchStatus -->
    <!-- Конец декоративным меткам -->

    <!-- begin matchStatus -->

    <!-- end matchStatus -->
  </article>
  <!-- end meta -->

</transition>
</modal-container>
<?php if (Yii::$app->request->get('_pjax')): ?>
      <script type="text/javascript">

        window.ComponentsHandler.initSection('matchPopup');

      </script>
    <?php endif; ?>
 <?Pjax::end();?>