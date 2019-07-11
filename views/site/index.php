<?php
use app\models\Match;
use app\models\DotaHelper;
use app\models\News;
use yii\bootstrap\Html;
use yii\bootstrap\Modal;
use yii\helpers\StringHelper;
use yii\helpers\Url;
// use yii\db\Query;
use yii\widgets\Pjax;
use yii\widgets\ListView;

$this->title = 'ESX.BET';
$this->description = 'ESX.BET';


?>

<!-- begin banner -->
<aside class="banner relative" id="banner">
    <lazy-image :is-image-fill-container="true" :src="banner" absolute="">
    </lazy-image>
    <article class="container h-between v-centered height_fill parent row wrap h-centered_tablet">
        <section class="parent column banner__section h-centered_tablet">
            <h1 class="font-weight_bold banner__title color_white  margin-top_zero line-height_4">
                <?=Yii::t('main', 'ESX &mdash; это биржа ставок на киберспорт');?>
            </h1>
    
            <p class="font-weight_semibold margin-top_base banner__paragraph color_white  line-height_4">
                <?=Yii::t('main', 'Установи свои правила игры: создавай свои ставки с коэффициентами, которые подходят тебе.');?>
            </p>
        </section>

        <base-button :action="openRegistrationForm" class-name="banner__button font-weight_semibold" modifier="blue">
            <?=Yii::t('main', 'Сделать ставку');?>
        </base-button>
    </article>
</aside>
<!-- end banner -->

<!-- main content -->
<main id="main">
    <?Pjax::begin(['id'=>'day_matches','options' => ['class'=>'']]);?>
    <section-container title="<?=Yii::t('main', 'Матчи дня')?>">
        <!-- begin gameSorter -->
        <div class="grow gameSorter parent row v-centered margin-left_auto">
            <!-- begin gameSorter__button -->
            <!-- begin games -->
            <ul class="grow display_grid h-end v-centered games parent row">
              <li class="game margin-top_zero <?if(Yii::$app->request->get('dm_game')===null){?>grayscale_none<?}?>" role="presentation">
                <a aria-label="Выбрать все игры" href="<?=Url::to(['site/index'])?>" class="unstyledLink gameSorter__button baseChild circle  parent centered margin-top_small-phone <?if(Yii::$app->request->get('dm_game')===null){?>button_blue<?} else{?>button_gray<?}?>">
                   <icon name="list-ul">
               </a>
              </li>
              <li key="index" class="game margin-top_zero <?if(Yii::$app->request->get('dm_game')==0&&Yii::$app->request->get('dm_game')!==null){?>game_active<?} else{?>opacity_half<?}?>">

                     <a data-id="0" aria-label="DOTA 2" class="parent margin-top_small-phone" href="<?=Url::to(['site/index', 'dm_game'=>0])?>">
                        <lazy-image src="/img/game_0.png" alt="DOTA 2" title="DOTA 2" relative>
                    </a>
              </li>
              <li key="index" class="game margin-top_zero <?if(Yii::$app->request->get('dm_game')==1){?>game_active<?} else{?>opacity_half<?}?>">

                     <a data-id="1" aria-label="CS:GO" class="parent margin-top_small-phone" href="<?=Url::to(['site/index', 'dm_game'=>1])?>">
                        <lazy-image src="/img/game_1.png" alt="CS:GO" title="CS:GO" relative>
                    </a>
              </li>
            </ul>
            <!-- end games -->

        </div>
        <!-- end gameSorter -->


        <!-- begin matchesSlider -->
        <div class="width_fill matchesSlider margin_centered relative">
            <!-- begin tiny-slider -->
            <tiny-slider :fixed-width="233" :gutter="29" :loop="false" :mouse-drag="true" :responsive="responsive" controls-container=".sliderControls" items="4">
                <!-- Инкапсулированный компонент match-info -->
                <!-- Все данные можно помещать в аттрибуты этого компонента. -->
                <?php foreach ($model['today'] as $key => $match): ?>
                  <match-info 
                    :last-ratio-values="{
                          left: <?=$match->teamOneRatioLast?>,
                          right: <?=$match->teamTwoRatioLast?>
                    }"
                    game-id="<?=$match->id?>" 
                    game-image="/img/game_<?=$match->game?>.png" 
                    game-background="/img/11.png" 
                    league-name="<?=$match->league->name?>" 
                    league-stage=" " 
                    left-team-logo="https://24cyber.ru<?=$match->teamOne->logo?>" 
                    left-team-name="<?=$match->teamOne->name?>" 
                    left-team-ratio="<?=$match->teamOneRatio?>" 
                    match-date="<?=Yii::$app->formatter->asDate($match->date, 'php:m/d/Y H:i');?>" 
                    right-team-logo="https://24cyber.ru<?=$match->teamTwo->logo?>" 
                    right-team-name="<?=$match->teamTwo->name?>" 
                    right-team-ratio="<?=$match->teamTwoRatio?>"
                    match-url="<?=Url::to(['site/match-details', 'id'=>$match->id])?>"></match-info>
                <?php endforeach; ?>
            </tiny-slider>
            <!-- end tiny-slider -->

            <!-- begin sliderControls -->
            <div class="sliderControls absolute width_fill">
                <!-- begin sliderControls__button  -->
                <base-button class="sliderControls__button sliderControls__button_previous absolute centered parent circle shadow_white-block" id="previousMatchButton" label="Предыдущие матчи" unstyled="">
                    <chevron-icon position="left">
                    </chevron-icon>
                </base-button>
                <!-- end sliderControls__button  -->

                <!-- begin sliderControls__button  -->
                <base-button class="sliderControls__button sliderControls__button_next absolute centered parent circle shadow_white-block" id="nextMatchButton" label="Следующие матчи" unstyled="">
                    <chevron-icon position="right">
                    </chevron-icon>
                </base-button>
                <!-- end sliderControls__button  -->

            </div>
            <!-- end sliderControls -->
        </div>
        <!-- end matchesSlider -->
    </section-container>
    <?php if (Yii::$app->request->get('_pjax')): ?>
      <script type="text/javascript">

        window.ComponentsHandler.initSection('main');

      </script>
    <?php endif; ?>

    <?Pjax::end();?>
    <!-- end matchesSection -->

    <!-- begin betsSection -->
    <?Pjax::begin(['id'=>'all_matches','options' => ['class'=>'']]);?>
    <section-container title="<?=Yii::t('main', 'Биржа ставок')?>">
        <!-- begin gameSorter -->
        <div class="grow gameSorter parent row v-centered margin-left_auto">
            <!-- begin gameSorter__button -->
            <!-- begin games -->
            <ul class="grow display_grid h-end v-centered games parent row">
              <li class="game margin-top_zero <?if(Yii::$app->request->get('am_game')===null){?>grayscale_none<?}?>" role="presentation">
                <a aria-label="Выбрать все игры" href="<?=Url::to(['site/index'])?>" class="unstyledLink gameSorter__button baseChild circle  parent centered margin-top_small-phone <?if(Yii::$app->request->get('am_game')===null){?>button_blue<?} else{?>button_gray<?}?>">
                   <icon name="list-ul">
               </a>
              </li>
              <li key="index" class="game margin-top_zero <?if(Yii::$app->request->get('am_game')==0&&Yii::$app->request->get('am_game')!==null){?>game_active<?} else{?>opacity_half<?}?>">

                     <a data-id="0" aria-label="DOTA 2" class="parent margin-top_small-phone" href="<?=Url::to(['site/index', 'am_game'=>0])?>">
                        <lazy-image src="/img/game_0.png" alt="DOTA 2" title="DOTA 2" relative>
                    </a>
              </li>
              <li key="index" class="game margin-top_zero <?if(Yii::$app->request->get('am_game')==1){?>game_active<?} else{?>opacity_half<?}?>">

                     <a data-id="1" aria-label="CS:GO" class="parent margin-top_small-phone" href="<?=Url::to(['site/index', 'am_game'=>1])?>">
                        <lazy-image src="/img/game_1.png" alt="CS:GO" title="CS:GO" relative>
                    </a>
              </li>
            </ul>
            <!-- end games -->

        </div>
        <!-- end gameSorter -->

        <!-- begin bets -->
        <div class="width_fill bets margin-top_29 shadow_spread">
            <!-- begin betsSorter -->
            <form method="get" class="betsSorter font-size_12" action="#">
                <!-- begin sorterSection -->
                <fieldset class="margin_vertical-base-tablet sorterSection sorterSection_styled sorterSection_zeroVerticalPadding margin_centered-phone">
                  <legend class="visible-hidden"><?=Yii::t('main', 'Сортировка по состоянию матчей')?></legend>
                  <!-- begin  stateMatchesSorter -->
                  <div class="stateMatchesSorter parent h-between height_fill wrap">

                    <input id="live-bets-input" v-model="betsSorter.status" type="radio" name="betsSorterStatus" value="liveBets" <?if(Yii::$app->request->post('status')=='liveBets'){?>checked="checked"<?}?> @click="sortBets" disabled>
                    <label id="live-bets-label" for="live-bets-input" class="betsSorter__label round_25 relative text_centered">
                      Live ставки
                    </label>
                    <input id="all-input" v-model="betsSorter.status" type="radio" name="betsSorterStatus" value="all" <?if(Yii::$app->request->post('status')=='all'){?>checked="checked"<?}?> @click="sortBets">
                    <label id="all-label" for="all-input" class="betsSorter__label round_25 relative text_centered">
                      Все матчи
                    </label>
                    <input id="results-input" <?if(Yii::$app->request->post('status')=='results'){?>checked="checked"<?}?> v-model="betsSorter.status" type="radio" name="betsSorterStatus" value="results" @click="sortBets">
                    <label id="results-label" for="results-input" class="betsSorter__label round_25 relative text_centered">
                      Результаты
                    </label>
                  </div>
                  <!-- end  stateMatchesSorter -->
                </fieldset>
                <!-- end sorterSection -->

                <!-- begin sorterSection -->
                <fieldset class="sorterSection sorterSection_styled margin_vertical-base-tablet margin_centered-phone">
                  <legend class="visible-hidden"><?=Yii::t('main', 'Дата:')?></legend>
                  <!-- begin timeRangeSorter -->
                  <sorter-date 
                    :default-dates="{
                      until: betsSorter.untilDate,
                      since: betsSorter.sinceDate
                    }" 
                    since-date-name="betsSorterSinceDate" 
                    until-date-name="betsSorterUntilDate" 
                    @set-since-date="betsSorter.sinceDate = $event;sortBets();" 
                    @set-until-date="betsSorter.untilDate = $event;sortBets();"></sorter-date>
                  <!-- end timeRageSorter -->
                </fieldset>
                <!-- end sorterSection -->

                <!-- begin sorterSection -->
                <fieldset class="relative sorterSection sorterSection_styled index_big margin_vertical-base-tablet margin_centered-phone">
                  <!-- Для настройки типов сортировки можно преедать массив из объектов в атрибут sorter types. Настройки состояния находятся в src/store/bets.  -->
                  <sorter-types :is-shown="$store.state.bets.isSorterTypesShown" :sorter-types="<?=str_replace('"', "'", json_encode($model['leagues']))?>" :on-choose-sorter-type="switchSorterTypeAndSort" :switch-display-of-sorter-types="switchSorterTypesState" :current-sorter-type="$store.state.bets.currentSorterType" list-label="Список вариантов сортировки матчей"></sorter-types>
                  <!-- end typeSorter -->
                </fieldset>
                <!-- end sorterSection -->

                <!-- begin sorterSection -->
                <fieldset class="margin_vertical-base-tablet sorterSection sorterSection_search margin_centered-phone">
                    <legend id="searchLabel" class="visible-hidden">
                        <?=Yii::t('main', 'Поиск')?>
                    </legend>
                    <!-- begin searchSorter -->
                    <div class="searchSorter relative height_fill padding-bottom_5 padding-top_5">

                        <label for="searchSorter" class="searchSorter__label color_gray font-size_16 absolute position_base" aria-label="Поиск">
                          <search-icon>
                        </label>
                        <input id="searchSorter" v-model="betsSorter.search" role="search" placeholder="Поиск" type="search" class="searchSorter__input width_fill height_fill" name="searchSorter" aria-describedby="searchLabel" @input="sortWithDelay">
                        <!-- Динамическая сортировка @input="sortWithDelay" -->
                  </div>
                  <!-- end searchSorter -->
                </fieldset>
                <!-- end sorterSection -->
            </form>
            <!-- end betsSorter -->

            <!-- begin bets-table -->
            <!-- Если убрать атрибут is-bets-shown (необязательный атрибут), то прелоадера не будет - будет пустота. -->
            <bets-table :is-bets-shown="$store.state.bets.bets.length" ref="betsSorter" :on-change-sorter-settings="(sorterSettings) => {
                log(sorterSettings);
                
                $.pjax.reload({method: 'POST',
                  container:'#all_matches', 
                  data : sorterSettings,
                  push: false,
                  timeout: 3000,
                  replace: false
                }); 
              }" 
            @load-matches="() => {
                const requstMethod = 'POST';
                const requestUrl = $store.state.bets.choosenMatchUrl;
                const requestHeaders = {
                  'X-CSRF-Token': $store.state.csrftoken
                };
                const requestParametrs = {
                  url: requestUrl,
                  data: {},
                  method: requstMethod,
                  headers: requestHeaders
                };

                // https://github.com/axios/axios
                // const request =  axios(requestParametrs);

                const request = window.Promise.resolve({
                  status: 'ok',
                  data: {
                      matches: generatedMatches()
                  }

                });

                // Тестовая задержка.
                window.setTimeout(() => {

                  request.then((response) => {

                      $store.commit('bets/updateMatches', [
                          ...response.data.matches
                      ]);

                  }); // end request

                }, 1500);

              }">
              <?php foreach ($model['all'] as $key => $match): ?>
                <bet-info
                  :last-ratio-values="{
                          left: <?=(float)$match->teamOneRatioLast?>,
                          right: <?=(float)$match->teamTwoRatioLast?>
                    }"

                  :is-online="<?=$match->active?>"
                  :left-team-ratio="<?=$match->teamOneRatio?>"
                  :right-team-ratio="<?=$match->teamTwoRatio?>"
                  :is-completed="false"
                  :match-score="{
                    left: <?=$match->score_left?>,
                  right: <?=$match->score_right?>
                  }"
                  left-team-name="<?=$match->teamOne->name?>"
                  left-team-logo="https://24cyber.ru<?=$match->teamOne->logo?>" left-team-country="https://24cyber.ru<?=$match->teamOne->fl?>"
                  right-team-name="<?=$match->teamTwo->name?>"
                  right-team-logo="https://24cyber.ru<?=$match->teamTwo->logo?>" right-team-country="https://24cyber.ru<?=$match->teamTwo->fl?>" match-date="<?=Yii::$app->formatter->asDate($match->date, 'php:m/d/Y H:i');?>" league-name="<?=$match->league->name?>"
                  league-logo="https://24cyber.ru<?=$match->league->image?>"
                  match-url="<?=Url::to(['site/match-details', 'id'=>$match->id])?>"
                >

                </bet-info>
              <?php endforeach; ?>
              <?php if ($seil>0): ?>
                
                <base-button id="load_more" 
                  :action="() => {
                    log(betsSorter);
                    betsSorter.page = $('#load_more').data('npage');
                    $.pjax.reload({method: 'POST',
                      container:'#all_matches', 
                      data : betsSorter,
                      push: false,
                      timeout: 3000,
                      replace: false
                    }); 
                  }"
                  class-name="width_fill round_slight-force font-size_22" 
                  data-page="<?=$page?>"
                  data-npage="<?=$next_page?>">
                    <?=Yii::t('main', 'Показать больше')?>
                </base-button>
                
              <?php endif ?>
              
              
            </bets-table>
            <!-- end bets-table -->
        </div>
        <!-- end bets -->

    </section-container>
    <?php if (Yii::$app->request->get('_pjax')||Yii::$app->request->isAjax): ?>
      <script type="text/javascript">

        window.ComponentsHandler.initSection('main');

      </script>
    <?php endif; ?>

    <?Pjax::end();?>
    <!-- end betsSection -->

  
</main>

