<?php
use app\models\Orders;
use app\models\Transaction;

?>
  <personal-room id="personalRoom" ref="personalRoom" v-if="$store.state.popups[personalRoomPopupName]" :on-change-transactions-sorter-settings="(sorterSettings) => {
    log(sorterSettings);
    const request =  axios({
     url: 'user/transactions',
     method : 'POST',
     data : {data : sorterSettings, 'xcsrf' : '<?=\Yii::$app->request->csrfToken?>'}
    });
    request.then((response) => {
      $store.commit(
            `personalRoom/setUserData`,
            {
              moduleName: 'transactions',
              newUserData: response.data
            }
        );
    });
  }" :on-change-created-bets-sorter-settings="(sorterSettings) => {
    const request =  axios({
     url: 'user/orders',
     method : 'POST',
     data : {data : sorterSettings, 'xcsrf' : '<?=\Yii::$app->request->csrfToken?>'}
    });
    request.then((response) => {
      $store.commit(
            `personalRoom/setUserData`,
            {
              moduleName: 'createdBets',
              newUserData: response.data
            }
        );
    });
  }" :on-change-accepted-bets-sorter-settings="(sorterSettings) => {
    log(sorterSettings);
    const request =  axios({
     url: 'user/pays',
     method : 'POST',
     data : {data : sorterSettings, 'xcsrf' : '<?=\Yii::$app->request->csrfToken?>'}
    });
    request.then((response) => {
      $store.commit(
            `personalRoom/setUserData`,
            {
              moduleName: 'acceptedBets',
              newUserData: response.data
            }
        );
    });
  }" :on-change-contact-center-sorter-settings="(sorterSettings) => {
    log(sorterSettings);
  }" @open-personal-room="(context) => {

  const method = 'GET';
  const requestUrl = 'user/data';



  // https://github.com/axios/axios
  const request =  axios({
   url: requestUrl,
   method,
  })

  // Тестовая задержка.
  request.then((response) => {
  // В src/constants/personalRoom в объекте testUserData можно найти пример данных.

  log(response.data);
  $nextTick(() => {

    $store.commit(
          `personalRoom/setUserData`,
          {
            moduleName: 'transactions',
            newUserData: response.data.newUserTransactionsData
          }
      );

      $store.commit(
          `personalRoom/setUserData`,
          {
            moduleName: 'createdBets',
            newUserData: response.data.newUserCreatedBets
          }
      );

      $store.commit(
          `personalRoom/setUserData`,
          {
            moduleName: 'acceptedBets',
            newUserData: response.data.newUserAcceptedBets
          }
      );

      $store.commit(
          `personalRoom/setUserData`,
          {
            moduleName: 'contactCenter',
            newUserData: response.data.newUserContactCenterData
          }
      );
      window.setTimeout(function(){
        log(getRangeOfData($store.getters['personalRoom/acceptedBetsByTimeDecreasing'], 'acceptedBets'));
      },300);


  });

    setLoadedData();
    context.isLoadedData = true;
  });

}">
    <div :class="{
'shadow_spread personalRoomUserPanel sticky position_base height_fill background-color_white index_big': true
}">
      <!-- begin userInfo -->
      <div class="userInfo parent column centered relative margin_centered">
        <!-- begin userInfoMessages -->
        <!-- end userInfoMessages -->
        <base-button :action="switchView(viewNames.contactCenter)" :class="`userInfoMessages absolute`" label="<?=Yii::t('profile', 'Перейти к сообщениям')?>" unstyled>
          <icon :class="`userInfoMessages__icon ${personalRoom.currentOpenedView === viewNames.contactCenter ? ' userInfoMessages__icon_blue' : ''}`" name="envelope"></icon>
          <span class="absolute userInfoMessages__quantity parent centered circle padding_container-small background-color_blue color_white">
  [[ user.contactCenter.length ]]
</span>
        </base-button>
        <lazy-image :src="'<?=Yii::$app->user->identity->avatar?>'" :fit-block="false" class-name="userInfoAvatar overflow_hidden circle parent centered relative">
          <file-upload ref="uploadAvatar" extensions="gif,jpg,jpeg,png,webp" accept="image/png,image/gif,image/jpeg,image/webp" name="avatar" class="absolute_force position_base userInfoAvatar__button width_fill height_fill parent_force centered color_white font-size_12"
            v-model="avatar.files" @input-filter="avatarFilter" @input-file="(newFile, oldFile, prevent) => {
                if (newFile && !oldFile) {
                  let data = new FormData();
                  data.append('avatar', newFile.file);
                  data.append('xcsrf', '<?=\Yii::$app->request->csrfToken?>');


                  const requestUrl = 'user/add-avatar';
                  const requestMethod = 'post';
                  const requestHeaders = {
                    'Content-Type': 'multipart/form-data'
                  };
                  const requestOptions = {
                    method: requestMethod,
                    url: requestUrl,
                    headers: requestHeaders,
                    data
                  };
                  const request = axios(requestOptions);

                  request
                    .then((response) => {
                      log('Successful upload an user avatar!');
                    })
                    .catch((error) => {
                      log(`There is some error happend in the request. ${error}`);
                    })
                }
              }">
            <?=Yii::t('profile', 'Изменить аватар')?>
          </file-upload>
        </lazy-image>
        <!-- begin userInfo__title -->
        <div class="margin-top_base parent row h-between v-centered margin-top_zero-tabletLess">
          <h2 class="userInfo__title font-size_18 font-weight_semibold">
  <?=Yii::$app->user->identity->username?>
</h2>
          <base-button :action="() => { document.location.replace('/logout'); }" modifier="blue" class-name="circle color_white font-size_10" label="Выйти из аккаунта" is-circle>
            <svg aria-hidden="true" role="img" width="1em" height="1em" viewBox="0 0 512 512">
  <path fill="currentColor" d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path>
</svg>
          </base-button>
        </div>
        <!-- end userInfo__title -->
        <!-- begin userInfo__balance -->
        <p class="userInfo__balance color_blue font-size_20 font-weight_bold margin-top_small margin-top_zero-tabletLess">
          $
          <?=Yii::$app->user->identity->balance?>
        </p>
        <!-- end userInfo__balance -->
      </div>
      <!-- end userInfo -->


      <nav class="personalRoomNavigation sticky index_big" aria-label="<?=Yii::t('profile', 'Навигация по личному кабинету')?>">
        <ul class="personalRoomNavigationList font-size_14 height_fill v-end">
          <li v-for="(link, index) in personalRoom.links" :key="index" class="personalRoomSectionLink parent row margin-top_zero-tabletLess">

            <base-button :action="switchView(link.viewName)" :class-name="`personalRoomSectionLink__button parent row v-centered font-weight_semibold font-size_14 width_fill${personalRoom.currentOpenedView === link.viewName ? ' personalRoomSectionLink__button_active' : ''}`"
              unstyled>
              <lazy-image :src="link.icon" :fit-block="false" :class-name="`personalRoomSectionLink__icon background-color_${personalRoom.currentOpenedView === link.viewName ? 'blue' : 'darkBlue'}`"></lazy-image>

              <span class="personalRoomSectionLink__label color_darkBlue">
      [[ link.label ]]
    </span>
            </base-button>
          </li>
        </ul>
      </nav>
    </div>
    <!-- end personalRoomUserPanel -->

    <!-- begin personalRoom -->
    <div class="grow personalRoom relative height_fill">
      <transition name="switchFading" appear>
        <!-- begin personalRoomBalance -->
        <section v-if="personalRoom.currentOpenedView === viewNames.balance && (user.transactions.length || isLoadedData)" class="personalRoomBalance display_grid grid-rows_fill height_fill">

          <!-- begin userPaymentActionButtons -->
          <div class="userPaymentActionButtons parent row v-end wrap centered_tablet padding_horizontal-tablet">
            <base-button :action="changeUserPaymentAction({
      userAction: userPaymentActions.deposit,
      availabledPayments: [
  'visa',
  'mastercard',
  'bitcoin'
      ]
  })" :modifier="personalRoom.currentPaymentAction === userPaymentActions.deposit ?
      'blue shadow_big-spread' :
      'strokeBlue'
    " class-name="userPaymentActionButtons__button font-weight_semibold">
              <?=Yii::t('profile', 'Депозит')?>
            </base-button>

            <base-button :action="changeUserPaymentAction({
      userAction: userPaymentActions.withdrawal,
      availabledPayments: [
  'visa',
  'mastercard',
  'bitcoin',
  'paypal',
  'webmoney',
  'ether',
      ]
  })" :modifier="personalRoom.currentPaymentAction === userPaymentActions.withdrawal ?
      'blue shadow_big-spread' :
      'strokeBlue'
    " class-name="userPaymentActionButtons__button font-weight_semibold">
              <?=Yii::t('profile', 'Вывод')?>
            </base-button>

          </div>
          <!-- end userPaymentActionButtons -->

          <!-- begin paymentsWays -->
          <ul class="userPaymentWays parent row v-end font-weight_semibold wrap padding_horizontal-tablet centered_tablet padding-bottom_large-tablet">
            <li v-for="(payment, index) in personalRoom.payments.ways" :key="index" :class="[
      'padding-top_increased paymentWay parent row relative after after_absolute',
       personalRoom.payments.availabled.indexOf(payment.id) !== -1 ? 'paymentWay_active' : 'paymentWay_disabled'
   ]">

              <a :href="payment.link" class="parent column centered width_fill" @click.prevent="openLinkInNewWindowIfAvailable($event)(
        personalRoom.payments.availabled.indexOf(payment.id) !== -1
      )">
                <lazy-image :src="payment.icon" :fit-block="false" class-name="paymentWay__icon"></lazy-image>
                <span class="margin-top_base paymentWay__label">
        [[ payment.label ]]
      </span>
              </a>
            </li>
          </ul>
          <!-- end userPaymentWays -->


          <!-- begin userTransactions -->

          <article class="userTransactions sorterContainer someBetContainer_tablet parent row wrap v-centered background-color_white">
            <h2 class="font-size_16 font-weight_semibold sorterContainer__title headingOffset_tablet"><?=Yii::t('profile', 'Транзакции')?>:</h2>
            <!-- begin transactionsSorter -->
            <form class="transactionsSorter display_grid grow margin-top_small-tabletLess grid_three-phoneUp" action="" method="get">

              <sorter-date until-date-name="transactionsUntilDate" since-date-name="transactionsSinceDate" styled @set-until-date="transactions.untilDate = $event;sortTransactions();" @set-since-date="transactions.sinceDate = $event;sortTransactions();"></sorter-date>
              <!-- personalRoom[transactionBetTypeSorterName].types -->
              <sorter-types :is-shown="personalRoom[transactionBetTypeSorterName].isListOpened" :sorter-types="[
                      {
                        id: 0,
                        name: '<?=Yii::t('profile', 'Ставка')?>'
                      },
                      {
                        id: 1,
                        name: '<?=Yii::t('profile', 'Максимальная ставка')?>'
                      },
                      {
                        id: 2,
                        name: '<?=Yii::t('profile', 'Минимальная ставка')?>'
                      }
                    ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                    {
              sorterTypesName: transactionBetTypeSorterName,
              sortFunctionName: 'sortTransactions'
            }
          )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(transactionBetTypeSorterName)" :current-sorter-type="personalRoom[transactionBetTypeSorterName].currentSorterType.name" :z-index="3" list-label="<?=Yii::t('profile', 'Список вариантов сортировки транзакций по ставкам')?>"
                styled></sorter-types>

              <sorter-types :is-shown="personalRoom[transactionActionTypeSorterName].isListOpened" :sorter-types="[
                      {
                        id: 0,
                        name: '<?=Yii::t('profile', 'Тип')?>'
                      },
                      {
                        id: 1,
                        name: '<?=Yii::t('profile', 'Депозит')?>'
                      },
                      {
                        id: 2,
                        name: '<?=Yii::t('profile', 'Вывод')?>'
                      }
                    ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                    {
              sorterTypesName: transactionActionTypeSorterName,
              sortFunctionName: 'sortTransactions'
            }
          )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(transactionActionTypeSorterName)" :current-sorter-type="personalRoom[transactionActionTypeSorterName].currentSorterType.name" :z-index="2" list-label="<?=Yii::t('profile', 'Список вариантов сортировки транзакций по ставкам')?>"
                styled></sorter-types>

              <sorter-types :is-shown="personalRoom[transactionStatusSorterName].isListOpened" :sorter-types="[
                      {
                        id: 0,
                        name: '<?=Yii::t('profile', 'Статус')?>'
                      },
                      <?php foreach (Transaction::getStatuses() as $key => $status): ?>
                      {
                          id: <?=($key+1)?>,
                          name: '<?=$status?>'
                        },
                      <?php endforeach ?>
                    ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                    {
              sorterTypesName: transactionStatusSorterName,
              sortFunctionName: 'sortTransactions'
            }
          )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(transactionStatusSorterName)" :current-sorter-type="personalRoom[transactionStatusSorterName].currentSorterType.name" :z-index="1" list-label="<?=Yii::t('profile', 'Список вариантов сортировки транзакций по ставкам')?>"
                styled></sorter-types>

            </form>
            <!-- end transactionsSorter -->
          </article>
          <!-- end transactionSorterContainer  -->
          <!-- begin someBetsTable -->

          <article role="table" aria-label="<?=Yii::t('profile', 'Транзакции пользователя')?>" class="someBetsTable background-color_white" aria-describedby="transactionsTable_description">
            <h2 id="transactionsTable_description" class="visible-hidden">
      <?=Yii::t('profile', 'Таблица транзакция пользователя')?>
    </h2>
            <!-- begin userTransactionsHead -->
            <user-transactions-header></user-transactions-header>
            <!-- end userTransactionsHead -->

            <!-- begin userTransactionsBody -->
            <fade-translate-transition-group :delay="50" role="tablerows" tag="div" class="userTransactionsBody v-centered display_grid padding-top_quarter">

              <user-transaction v-for="(transaction, index) in getRangeOfData($store.getters['personalRoom/transactionsByTimeDecreasing'], 'transactions')" :key="index" :data-index="index" :transaction-date="transaction.date" :user-action="transaction.action" :transaction-status="transaction.status.processed"
                :sum="transaction.sum" :commission="transaction.commission" :rest="transaction.rest"></user-transaction>

            </fade-translate-transition-group>
            <!-- end userTransactionsBody -->

            <!-- begin transactionPagesPagination -->
            <data-pagination :switch-page="switchPage" :quantity-pages="paginations.transactions.quantityPages" :current-page-number="paginations.transactions.currentPage" data-name="Transactions"></data-pagination>
            <!-- end transactionPagesPagination -->

          </article>
          <!-- end  transactionsTable -->

        </section>
        <icon-preloader v-else-if="personalRoom.currentOpenedView === viewNames.balance && !user.transactions.length" class-name="padding-top_large padding-bottom_large" vertical-position="start"></icon-preloader>
        <!-- end personalRoomBalance -->
      </transition>
      <!-- end userTransactions -->


      <!-- begin userSomeBets -->
      <transition name="switchFading" appear>

        <section v-if="personalRoom.currentOpenedView === viewNames.createdBets &&  (user.createdBets.length || isLoadedData)"
          class="userSomeBets background-color_white display_grid grid-rows_fill height_fill personalRoomSection_phone">
          <!-- begin userCreatedBetsSorter -->
          <article class="userSomeBetsSorterContainer parent row wrap v-centered padding_horizontal-tablet padding-bottom_small-bet-tablet h-between someBetContainer_tablet">
            <h2 class="font-weight_semibold userSomeBetsSorterContainer__title font-size_18"><?=Yii::t('profile', 'Созданные  ставки')?>:</h2>
            <!-- begin userSomeBetsSorter -->
            <form class="userSomeBetsSorter personalRoomSorter display_grid margin-top_base-tabletLess width_fill-tablet grid_three-phoneUp" action="" method="get">

              <sorter-date until-date-name="createdBetsUntilDate" since-date-name="createdBetsSinceDate" styled @set-until-date="createdBets.untilDate = $event;sortCreatedBets();" @set-since-date="createdBets.sinceDate = $event;sortCreatedBets();"></sorter-date>

              <sorter-types :is-shown="personalRoom[createdBetsGameName].isListOpened" :sorter-types="[
                      {
                        id: 0,
                        name: '<?=Yii::t('profile', 'Игра')?>'
                      },
                      {
                        id: 1,
                        name: 'DOTA 2'
                      },
                      {
                        id: 2,
                        name: 'CSGO'
                      },

                    ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                    {
              sorterTypesName: createdBetsGameName,
              sortFunctionName: 'sortCreatedBets'
            }
          )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(createdBetsGameName)" :current-sorter-type="personalRoom[createdBetsGameName].currentSorterType.name" :z-index="3" list-label="Список сортировки созданных ставок по играм" styled></sorter-types>

              <sorter-types :is-shown="personalRoom[createdBetsStatusName].isListOpened" :sorter-types="[
                      {
                        id: 0,
                        name: 'Статус'
                      },
                      <?php foreach (Orders::getStatuses() as $key => $status): ?>
                  {
                        id: <?=($key+1)?>,
                        name: '<?=$status?>'
                      },
                <?php endforeach ?>
                    ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                    {
              sorterTypesName: createdBetsStatusName,
              sortFunctionName: 'sortCreatedBets'
            }
          )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(createdBetsStatusName)" :current-sorter-type="personalRoom[createdBetsStatusName].currentSorterType.name" :z-index="2" list-label="<?=Yii::t('profile', 'Список сортировки созданных ставок по их статусу')?>"
                styled></sorter-types>
            </form>
            <!-- end transactionsSorter -->
          </article>
          <!-- end userCreatedBetsSorter  -->

          <!-- begin someBetsTable -->

          <article role="table" aria-label="<?=Yii::t('profile', 'Созданные ставки')?>" class="someBetsTable background-color_white" aria-describedby="createdBetsTable_description">
            <h2 id="createdBetsTable_description" class="visible-hidden">
      <?=Yii::t('profile', 'Таблица созданых ставок')?>
    </h2>

            <some-bets-table-head></some-bets-table-head>

            <!-- begin userChoosenBet -->

            <user-choosen-bet v-if="user.choosenCreatedBet && user.choosenCreatedBet.isShown" :bet-id="user.choosenCreatedBet.bet.id" :bet-status="user.choosenCreatedBet.bet.status" :game-image="user.choosenCreatedBet.game.image" :left-team-logo="user.choosenCreatedBet.leftTeam.logo"
              :win-team-logo="user.choosenCreatedBet.winTeam.logo" :right-team-logo="user.choosenCreatedBet.rightTeam.logo" :match-date="user.choosenCreatedBet.match.date" :win-team-name="user.choosenCreatedBet.winTeam.name" :left-team-name="user.choosenCreatedBet.leftTeam.name"
              :right-team-name="user.choosenCreatedBet.rightTeam.name" :note-about-end-match="user.choosenCreatedBet.match.noteAboutEndMatch" :ratio="$store.getters['personalRoom/createdBetsMean']" :sum="$store.getters['personalRoom/createdBetsCommonSum']"
              :accepted-sum="$store.getters['personalRoom/createdBetsCommonAcceptedSum']" :possible-win-sum="$store.getters['personalRoom/createdBetsCommonPossibleWinSum']"></user-choosen-bet>

            <!-- begin someBetsData -->
            <fade-translate-transition-group v-if="user.createdBets.length" :delay="50" :is-leave="false" tag="div" role="rowgroup" class="someBetsData display_grid font-size_10 v-start padding-top_base grid-rows_fill">

              <user-bet v-for="(createdBet, index) in getRangeOfData($store.getters['personalRoom/createdBetsByTimeDecreasing'], 'createdBets')" :key="index" :data-index="index" :bet-id="createdBet.bet.id" :game-image="createdBet.game.image" :left-team-logo="createdBet.leftTeam.logo"
                :win-team-logo="createdBet.winTeam.logo" :right-team-logo="createdBet.rightTeam.logo" :bet-status="createdBet.bet.status" :match-date="createdBet.match.date" :win-team-name="createdBet.winTeam.name" :left-team-name="createdBet.leftTeam.name"
                :right-team-name="createdBet.rightTeam.name" :note-about-end-match="createdBet.match.noteAboutEndMatch" :ratio="createdBet.bet.ratio" :sum="createdBet.bet.sum" :accepted-sum="createdBet.bet.acceptedSum" :is-buttons-shown="{
                    accept: createdBet.buttonsDisplay.accept,
                    cancel: createdBet.buttonsDisplay.cancel,
                    delete: createdBet.buttonsDisplay.delete
                  }" bet-type="createdBet" @accept-bet="(userData) => {
           log(userData);

           const requestUrl = 'user/pay-order';
           const requestOptions = {
             method: 'post',
             url: requestUrl,
             data: {'order_id' : userData.betId, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
           };

          const request = axios(requestOptions);

           window.setTimeout(() => {
             request.then(response => {
               const isError = response.status !== 200;
               const message = response.data.message;
               const responseOptions = {
                 message,
                 isError,
                 isConfirmBlock: true
               };

               $store.dispatch('openNotificationPopup');
               $store.dispatch('authorization/showResponseMessage', responseOptions);
                $store.commit('personalRoom/setUserBalance', balance);
               log(requestStatus, 'after-request');
             });
           }, 500);
        }" @cancel-bet="(userData) => {
          log(userData);
          const requestUrl = 'user/off-order';
          const requestOptions = {
            method: 'post',
            url: requestUrl,
            data: {'order_id' : userData.betId, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}

          };

          const request = axios(requestOptions);


          window.setTimeout(() => {
            request.then(response => {
              const isError = response.status !== 200;
              const message = response.data;
              const responseOptions = {
                message,
                isError
              };

              $store.dispatch('authorization/showResponseMessage', responseOptions);

              log(requestStatus, 'after-request');
            });
          }, 500);
        }" @delete-bet="(userData) => {
          log(userData);
          const requestUrl = 'user/delete-order';
          const requestOptions = {
            method: 'post',
            url: requestUrl,
            data: {'order_id' : userData.betId, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
          };

          const request = axios(requestOptions);

          window.setTimeout(() => {
            request.then(response => {
              const isError = response.status !== 200;
              const message = response.data;
              const responseOptions = {
                message,
                isError
              };

              $store.dispatch('authorization/showResponseMessage', responseOptions);

              log(requestStatus, 'after-request');
            });
          }, 500);
        }">


                <some-bet-statistic v-if="createdBetStatistic.betPortalId" :ratio="createdBetStatistic.ratio" :sum="createdBetStatistic.sum" :accepted-sum="createdBetStatistic.acceptedSum" :possible-win-sum="createdBetStatistic.ratio * createdBetStatistic.sum" :bet-portal-id="createdBetStatistic.betPortalId"></some-bet-statistic>
              </user-bet>

            </fade-translate-transition-group>
            <!-- end someBetsData -->
            <!-- begin transactionPagesPagination -->
            <data-pagination :switch-page="switchPage" :quantity-pages="paginations.createdBets.quantityPages" :current-page-number="paginations.createdBets.currentPage" data-name="CreatedBets"></data-pagination>
            <!-- end transactionPagesPagination -->
          </article>
          <!-- end someBetsTable -->

        </section>
        <icon-preloader v-else-if="personalRoom.currentOpenedView === viewNames.createdBets && !user.createdBets.length" class-name="padding-top_large padding-bottom_large" vertical-position="start"></icon-preloader>

        <!-- end userSomeBets -->
      </transition>
      <!--  -->


      <transition name="switchFading" appear>
        <!-- begin userSomeBets -->
        <section
          v-if="personalRoom.currentOpenedView === viewNames.acceptedBets && (user.acceptedBets.length || isLoadedData)"
          class="userSomeBets background-color_white display_grid grid-rows_fill height_fill personalRoomSection_phone">
          <!-- begin userSomeBetsSorterContainer -->
          <article class="userSomeBetsSorterContainer parent row wrap v-centered padding_horizontal-tablet padding-bottom_small-bet-tablet h-between someBetContainer_tablet">
            <h2 class="font-weight_semibold userSomeBetsSorterContainer__title font-size_18"><?=Yii::t('profile', 'Принятые ставки')?>:</h2>

            <!-- begin userSomeBetsSorter -->
            <form class="userSomeBetsSorter personalRoomSorter display_grid margin-top_base-tabletLess width_fill-tablet grid_three-phoneUp" action="" method="get">

              <sorter-date until-date-name="acceptedBetsUntilDate" since-date-name="acceptedBetsSinceDate" styled @set-until-date="acceptedBets.untilDate = $event;sortAcceptedBets();" @set-since-date="acceptedBets.sinceDate = $event;sortAcceptedBets();"></sorter-date>

              <sorter-types :is-shown="personalRoom[acceptedBetsGameName].isListOpened" :sorter-types="[
                  {
                    id: 0,
                    name: '<?=Yii::t('profile', 'Игра')?>'
                  },
                  {
                    id: 1,
                    name: 'DOTA 2'
                  },
                  {
                    id: 2,
                    name: 'CSGO'
                  },

                ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                  {
            sorterTypesName: acceptedBetsGameName,
            sortFunctionName: 'sortAcceptedBets'
          }
        )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(acceptedBetsGameName)" :current-sorter-type="personalRoom[acceptedBetsGameName].currentSorterType.name" :z-index="3" list-label="<?=Yii::t('profile', 'Список сортировки созданных ставок по играм')?>" styled></sorter-types>

              <sorter-types :is-shown="personalRoom[acceptedBetsStatusName].isListOpened" :sorter-types="[
                  {
                    id: 0,
                    name: '<?=Yii::t('profile', 'Статус')?>'
                  },
                  <?php foreach (Transaction::getStatusesPay() as $key => $status): ?>
                  {
                      id: <?=($key+1)?>,
                      name: '<?=$status?>'
                    },
                <?php endforeach ?>
                ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                  {
            sorterTypesName: acceptedBetsStatusName,
            sortFunctionName: 'sortAcceptedBets'
          }
        )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(acceptedBetsStatusName)" :current-sorter-type="personalRoom[acceptedBetsStatusName].currentSorterType.name" :z-index="2" list-label="<?=Yii::t('profile', 'Список сортировки созданных ставок по их статусу')?>"
                styled></sorter-types>
            </form>
            <!-- end transactionsSorter -->
          </article>
          <!-- end userSomeBetsSorterContainer  -->

          <!-- begin someBetsTable -->
          <article role="table" aria-label="<?=Yii::t('profile', 'История принятых ставок')?>" class="someBetsTable background-color_white" aria-describedby="acceptedBetsTable_description">
            <h2 id="acceptedBetsTable_description" class="visible-hidden">
    <?=Yii::t('profile', 'Таблица историй принятых ставок')?>
  </h2>

            <some-bets-table-head></some-bets-table-head>

            <!-- begin userChoosenBet -->



            <!-- begin someBetsData -->
            <fade-translate-transition-group :delay="50" :is-leave="false" tag="div" role="rowgroup" class="someBetsData display_grid font-size_10 v-start padding-top_base grid-rows_fill">
              <user-bet v-for="(acceptedBet, index) in getRangeOfData($store.getters['personalRoom/acceptedBetsByTimeDecreasing'], 'acceptedBets')" :key="index" :data-index="index" :bet-id="acceptedBet.bet.id" :game-image="acceptedBet. game.image" :left-team-logo="acceptedBet.leftTeam.logo"
                :win-team-logo="acceptedBet.winTeam.logo" :right-team-logo="acceptedBet.rightTeam.logo" :bet-status="acceptedBet.bet.status" :match-date="acceptedBet.match.date" :win-team-name="acceptedBet.winTeam.name" :left-team-name="acceptedBet.leftTeam.name"
                :right-team-name="acceptedBet.rightTeam.name" :note-about-end-match="acceptedBet.match.noteAboutEndMatch" :ratio="acceptedBet.bet.ratio" :sum="acceptedBet.bet.sum" :accepted-sum="acceptedBet.bet.acceptedSum" :is-buttons-shown="{
          accept: acceptedBet.buttonsDisplay.accept,
          cancel: acceptedBet.buttonsDisplay.cancel,
          delete: acceptedBet.buttonsDisplay.delete
        }" bet-type="acceptedBet" @accept-bet="(userData) => {
         log(userData);

         const requestUrl = 'user/pay-order-pay';
         const requestOptions = {
           method: 'post',
           url: requestUrl,
           data: {'pay_id' : userData.betId, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
         };

         const request = axios(requestOptions);

         request.then(response => {
           const isError = response.status !== 200;
           const message = response.data;
           const responseOptions = {
             message,
             isError,
             isConfirmBlock: true
           };

           $store.dispatch('openNotificationPopup');
           $store.dispatch('authorization/showResponseMessage', responseOptions);
           
           log(requestStatus, 'after-request');
         });
      }" @cancel-bet="(userData) => {
        log(userData);
        const requestUrl = '/user/bet/cancel/';
        const requestOptions = {
          method: 'post',
          headers: {
            'X-CSRFToken': $store.state.csrftoken
          },
          url: requestUrl,
          data: userData
        };

        //const request = axios(requestOptions);
        const request = window.Promise.resolve({
          status: 200,
          serverMessage: '<?=Yii::t('profile', 'Мы отменили вашу ставку.')?>'
        });

        request.then(response => {
          const isError = response.status !== 200;
          const message = response.serverMessage;
          const responseOptions = {
            message,
            isError
          };

          $store.dispatch('authorization/showResponseMessage', responseOptions);
          log(requestStatus, 'after-request');
        });
      }" @delete-bet="(userData) => {
        log(userData);
        const requestUrl = 'user/delete-pay';
        const requestOptions = {
          method: 'post',
          headers: {
            'X-CSRFToken': $store.state.csrftoken
          },
          url: requestUrl,
          data: {'pay_id' : userData.betId, 'xcsrf':'<?=\Yii::$app->request->csrfToken?>'}
        };

        const request = axios(requestOptions);
      
        request.then(response => {
          const isError = response.status !== 200;
          const message = response.data;
          const responseOptions = {
            message,
            isError
          };

          $store.dispatch('authorization/showResponseMessage', responseOptions);
          log(requestStatus, 'after-request');
        });
      }">
                <some-bet-statistic v-if="acceptedBetStatistic.betPortalId" :ratio="acceptedBetStatistic.ratio" :sum="acceptedBetStatistic.sum" :accepted-sum="acceptedBetStatistic.acceptedSum" :possible-win-sum="acceptedBetStatistic.ratio * acceptedBetStatistic.sum"
                  :bet-portal-id="acceptedBetStatistic.betPortalId"></some-bet-statistic>
              </user-bet>

            </fade-translate-transition-group>
            <!-- end someBetsData -->

            <!-- begin transactionPagesPagination -->
            <data-pagination :switch-page="switchPage" :quantity-pages="paginations.acceptedBets.quantityPages" :current-page-number="paginations.acceptedBets.currentPage" data-name="AcceptedBets"></data-pagination>
            <!-- end transactionPagesPagination -->
          </article>
          <!-- end someBetsTable -->

        </section>
        <icon-preloader v-else-if="personalRoom.currentOpenedView === viewNames.acceptedBets && !user.acceptedBets.length" class-name="padding-top_large padding-bottom_large" vertical-position="start"></icon-preloader>

      </transition>
      <!-- end userSomeBets -->


      <!-- begin personalContactCneter -->
      <transition name="switchFading" appear>
        <section v-if="personalRoom.currentOpenedView === viewNames.contactCenter && (user.contactCenter.length || isLoadedData)"
          class="personalRoomContactCenter background-color_lightGray display_grid grid-rows_fill height_fill personalRoomSection">
          <!-- begin userSomeBetsSorterContainer -->
          <article class="userSomeBetsSorterContainer parent row wrap v-centered padding_horizontal-tablet padding_vertical-bet-tablet h-between someBetContainer_tablet">
            <h2 class="font-weight_semibold userContactCenterSorterContainer__title font-size_18"><?=Yii::t('profile', 'Контакт-центер')?>:</h2>

            <!-- begin userSomeBetsSorter -->
            <form class="userContactCenterSorter personalRoomSorter display_grid margin-top_base-tabletLess width_fill-tablet grid_three-phoneUp" action="" method="get">

              <base-button :action="openNewThreadPopup" modifier="blue" class-name="userContactCenterSorter__button">
                <?=Yii::t('profile', 'Написать сообщение')?>
              </base-button>

              <sorter-date until-date-name="createdBetsUntilDate" since-date-name="createdBetsSinceDate" styled @set-until-date="contactCenter.untilDate = $event;sortContactCenter();" @set-since-date="contactCenter.sinceDate = $event;sortContactCenter();"></sorter-date>

              <sorter-types :is-shown="personalRoom[contactCenterStatusSorterName].isListOpened" :sorter-types="[
                  {
                    id: 0,
                    name: '<?=Yii::t('profile', 'Статус')?>'
                  },
                  {
                    id: 1,
                    name: '<?=Yii::t('profile', 'Выполнено')?>'
                  },
                  {
                    id: 2,
                    name: '<?=Yii::t('profile', 'Ожидается')?>'
                  }
                ]" :on-choose-sorter-type="switchSorterTypeAndSort(
                  {
            sorterTypesName: contactCenterStatusSorterName,
            sortFunctionName: 'sortContactCenter'
          }
        )" :switch-display-of-sorter-types="switchSorterTypesDisplayState(contactCenterStatusSorterName)" :current-sorter-type="personalRoom[contactCenterStatusSorterName].currentSorterType.name" :z-index="3" list-label="<?=Yii::t('profile', 'Сортировка истории сообщений по статусу сообщения')?>"
                styled></sorter-types>


            </form>
            <!-- end transactionsSorter -->
          </article>
          <!-- end userSomeBetsSorterContainer  -->
          <!-- begin someBetsTable -->

          <article role="table" class="contactCenterTable background-color_lightGray" aria-describedby="contactCenterTable_description">
            <h2 id="contactCenterTable_description" class="visible-hidden">
    <?=Yii::t('profile', 'Таблица сообщений пользователя')?>
  </h2>

            <contact-center-table-head></contact-center-table-head>

            <!-- begin userMessageHistory -->
            <fade-translate-transition-group v-if="!user.choosenThread.hash" :delay="50" :is-leave="false" tag="div" role="rowgroup" class="contactCenterData display_grid font-size_12 v-centered grid-rows_fill">

              <!-- begin userMessage -->

              <contact-center-user-message v-for="(userMessage, index) in getRangeOfData($store.getters['personalRoom/messagesByTimeDecreasing'], 'contactCenter')" :key="index" :data-index="index" :thread-id="userMessage.threadId" :last-answer-date="userMessage.lastAnswerDate"
                :title="userMessage.title" :status-name="userMessage.status.name" :status-message="userMessage.status.message" :is-shown="userMessage.messageId" class-name="background-color_white"></contact-center-user-message>
              <!-- end userMessage -->
            </fade-translate-transition-group>
            <!-- end userMessageHistory -->

            <!-- begin userThread -->
            <transition name="fadeTranslateToBottom" appear>
              <div v-if="user.choosenThread.isOpened" class="userThread grid-rows_fill font-size_12 display_grid background-color_white">
                <contact-center-user-message :thread-id="user.choosenThread.data.threadId" :last-answer-date="user.choosenThread.data.lastAnswerDate" :title="user.choosenThread.data.title" :status-name="user.choosenThread.data.status.name" :status-message="user.choosenThread.data.status.message"
                  :defined-hash="user.choosenThread.hash" class-name="background-color_lightGray"></contact-center-user-message>
                <!-- begin userThreadHistory -->
                <fade-translate-transition-group :delay="50" :is-leave="false" tag="div" class="userThreadHistory display_grid padding-top_quarter">
                  <persona-message v-for="(personaMessage, index) in getRangeOfData(user.choosenThread.data.dialog, 'choosenThread')" :key="index" :data-index="index" :is-user="personaMessage.isUser" :date="personaMessage.date" :message="personaMessage.message" :companion-name="user.choosenThread.data.companionName"
                    :images="personaMessage.images"></persona-message>
                </fade-translate-transition-group>
                <!-- ends userThreadHistory -->

                <!-- begin  threadUserMessage -->

                <form action="#" class="parent row padding_horizontal threadUserMessageContainer wrap" @submit.prevent="postUserMessage">

                  <label for="threadUserMessage" class="margin-right_base-rem font-weight_semibold padding-top_small-rem">
          <?=Yii::t('profile', 'Ответить:')?>
        </label>
                  <!-- begin messageLitter -->
                  <div class="messageLitter messageLitter_bloated-small background-color_lightGray parent column grow h-end margin-top_small-phone">
                    <!-- end messageLitter -->
                    <textarea id="threadUserMessage" v-model="choosenThread.message" class="width_fill grow background-color_null threadUserMessage__input" name="userMessage" placeholder="<?=Yii::t('profile', 'введите текст сообщения')?>"></textarea>
                    <!-- begin threadUserMessageFiles -->
                    <div class="threadUserMessageFiles padding-left_small parent row font-size_10-thread v-centered relative wrap">
                      <!-- begin file-upload -->
                      <file-upload ref="upload" v-model="choosenThread.files" :multiple="true" :size="1024 * 1024 * 10" class="threadUserMessageFiles__filesButton margin-top_small-phone" extensions="gif,jpg,jpeg,png,webp" accept="image/png,image/gif,image/jpeg,image/webp"
                        @input-filter="inputFilter" @input-file="inputFile">
                        Выбор файлов
                      </file-upload>
                      <!-- end file-upload -->
                      <!-- begin  threadUserMessageFiles__list -->
                      <transition-group v-if="choosenThread.files.length" tag="ul" name="fading" class="grow parent row wrap threadUserMessageFiles__list width_fill-phone margin-top_small-phone">

                        <li v-for="(file, index) in choosenThread.files" :key="file.id" class="margin-right_small margin-top_zero parent v-centered">
                          <span>[[ file.name ]]&nbsp;</span>
                          <base-button :action="removeFile(index)" class-name="removeFileButton parent v-s-centered" unstyled>
                            <icon-close></icon-close>
                          </base-button>
                          <span>;</span>
                        </li>
                      </transition-group>
                      <!-- end  threadUserMessageFiles__list -->

                      <p v-else class="margin-top_zero parent v-centered margin-top_small-phone">
                        <?=Yii::t('profile', 'Файлы не выбраны')?>
                      </p>
                      <base-button :action="() => {}" type="submit" modifier="blue" class-name="margin-left_auto threadUserMessageFiles__button v-s-end margin-top_base-phone">

                        [[ !choosenThread.status.processed ? '<?=Yii::t('profile', 'Отправить')?>' : '<?=Yii::t('profile', 'Отпаравляем')?>...' ]]
                      </base-button>
                      <!-- begin userHint -->
                      <transition name="fadeTranslateToTop" appear>
                        <p v-if="choosenThread.hint" class="absolute position_right position-bottom_full-offset color_red padding_small-container background-color_white round_slight shadow_litter font-size_18">
                          [[ choosenThread.hint ]]
                        </p>
                      </transition>

                      <!-- end userHint -->
                    </div>
                    <!-- end threadUserMessageFiles -->
                  </div>
                </form>
                <!-- end threadUserMessage -->

                <!-- begin userDialogPagination -->
                <data-pagination :switch-page="switchPage" :quantity-pages="user.choosenThread.data.dialog | getQuantityPages(paginations.choosenThread.dataPerPage, 'choosenThread')" :current-page-number="paginations.choosenThread.currentPage" data-name="ChoosenThread"
                  class-name="padding_horizontal font-size_11-thread" right-position bloated-pagination is-uncolored></data-pagination>
                <!-- end userDialogPagination -->
              </div>
            </transition>
            <!-- end userThread -->

            <!-- begin transactionPagesPagination -->
            <data-pagination :switch-page="switchContactCenterPage" :quantity-pages="paginations.contactCenter.quantityPages" :current-page-number="paginations.contactCenter.currentPage" data-name="ContactCenter"></data-pagination>
            <!-- end transactionPagesPagination -->
          </article>
          <!-- end someBetsTable -->

        </section>
        <icon-preloader v-else-if="personalRoom.currentOpenedView === viewNames.contactCenter && !user.contactCenter.length" class-name="padding-top_large padding-bottom_large min-height_offset" vertical-position="start"></icon-preloader>
      </transition>
      <!-- end personalRoomContactCenter -->



    </div>
    <!-- end personalRoom -->

    <!-- begin newThreadPopup -->

    <modal-container v-if="$store.state.popups[newThreadPopupName]" :on-close-modal="closeNewThreadPopup" class-name="relative parent row newThreadPopup" modifier="small" close-button-modifier="normal" small-litter>
      <form action="" class="newThreadForm formContainer parent column width_fill font-size_12" @submit.prevent="() => {
const newThread = user.newThread;
const message = newThread.message;
const title = newThread.title;

if (!message || !title) {
showThreadHint({
  hint: '<?=Yii::t('profile', 'Чтобы создать тему, следует заполнить оба поля')?>',
  moduleName: 'newThread'
});

return false;
}

// Month/Day/Year Hours/Minutes

const timestamp = getCurrentFormatedTime('MM/DD/YYYY HH:mm');
const max = 9999;
const min = 1000;
const threadId = getRandomId(min, max);

let requestUrl = '/api/current';
const requestHeaders = {
'X-CSRF-Token': $store.state.csrftoken
};
const requestParams = {
lastAnswerDate: timestamp,
userID: user.id,
threadId,
message,
title
};

const requestOptions = {
url: requestUrl,
data: requestParams,
method: 'POST',
headers: requestHeaders
};

// const request = axios(requestOptions);

const request = window.Promise.resolve({
status: 'ok'
});

window.setTimeout(() => {
request.then(response => {
  const isError = response.status !== 'ok';

  const newThreadData = {
    status: {
      message: 'Идёт обработка проблемы',
      name: ''
    },
    lastAnswerDate: timestamp,
    hash: `${threadId}`,
    date: timestamp,
    title,
    threadId
  };

  $store.dispatch('personalRoom/makeNewThreadAndOpenThread', {
    threadData: newThreadData,
    threadId,
    message
  });
});
}, 1000);


}">
        <label class="newThreadForm__label inputLabel" for="threadThemeInput">
<?=Yii::t('profile', 'Заголовок сообщения')?>
</label>
        <input id="threadThemeInput" v-model="user.newThread.title" type="text" class="newThreadForm__input input" name="threadTheam" placeholder="<?=Yii::t('profile', 'Моя проблема')?>">
        <label for="threadThemeInput" class="newThreadForm__label inputLabel">
<?=Yii::t('profile', 'Текст сообщения')?>
</label>
        <textarea id="threadMessageInput" v-model="user.newThread.message" class="newThreadForm__input newThreadForm__input_textarea input margin-bottom_large" name="threadMessage" placeholder="<?=Yii::t('profile', 'У меня есть проблема и, сейчас, я её опишу! В далёкой далёкой галактике...')?>"></textarea>

        <!-- begin newThreadFormSubmit -->
        <div class="parent h-end relative width_fill newThreadFormSubmit">
          <base-button type="submit" modifier="blue" class-name="newThreadFormSubmit__button formButton">
            <?=Yii::t('profile', 'Отправить')?>
          </base-button>
          <transition name="fadeTranslateToTop" appear>
            <p v-if="newThread.hint" class="absolute position_right position-bottom_full-offset color_red padding_small-container background-color_white round_slight shadow_litter font-size_18 newThreadFormSubmit__hint">
              [[ newThread.hint ]]
            </p>
          </transition>
          <!-- end userHint -->
        </div>
        <!-- end newThreadFormSubmit -->
      </form>
    </modal-container>
    <!-- end newThreadPopup -->

  </personal-room>