<?php
/* @var $this yii\web\View */
use yii\widgets\ListView;
use yii\helpers\Url;
$this->params['class_page'] = 'profile';
$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<personal-room id="personalRoom" ref="personalRoom" v-if="$store.state.popups[personalRoomPopupName]" :on-change-transactions-sorter-settings="(sorterSettings) => {
		log(sorterSettings);
	}" :on-change-created-bets-sorter-settings="(sorterSettings) => {
		log(sorterSettings);
	}" :on-change-accepted-bets-sorter-settings="(sorterSettings) => {
		log(sorterSettings);
	}" :on-change-contact-center-sorter-settings="(sorterSettings) => {
		log(sorterSettings);
	}" @open-personal-room="(context) => {

		const method = 'GET';
      	let requestUrl = '/api/current';
      	const headers = {
        	'X-CSRF-Token': $store.state.csrftoken
      	};
      	const requestParams = {
	        userId: user.id
	    };


		const perosnalRoomRequestUrls = {
			[viewNames.balance]: '/userTransactionsData/',
			[viewNames.contactCenter]: '/userContactCenterData/',
			[viewNames.createdBets]: '/userCreatedBetsData/',
			[viewNames.acceptedBets]: '/userAcceptedBetsData/',
		};

		const requestPart = perosnalRoomRequestUrls[personalRoom.currentOpenedView];

		if (!requestPart) {
			requestUrl += '/allUserData/';
		} else {
			requestUrl += requestPart;
		}



		// https://github.com/axios/axios
		// const request =  axios({
			//url: requestUrl,
			//data: matchData,
			//method,
			//headers
		//})

		const request = window.Promise.resolve({
			status: 'ok',
			userTransactions: testUserData.transactions,
			userCreatedBets: testUserData.createdBets,
			userAcceptedBets: testUserData.acceptedBets,
			userThreads: testUserData.contactCenter
		});

		// Тестовая задержка.
		window.setTimeout(() => {
			request.then((response) => {
				// В src/constants/personalRoom в объекте testUserData можно найти пример данных.
				let newUserTransactionsData = [];
				let newUserCreatedBets = [];
				let newUserContactCenterData = [];
				let newUserAcceptedBets = [];





				$nextTick(() => {
					for (let i = 0; i < 8; i++) {
						newUserTransactionsData = [
							...newUserTransactionsData,
							...response.userTransactions
						];
						newUserCreatedBets = [
							...newUserCreatedBets,
							...response.userCreatedBets
						];
						newUserAcceptedBets = [
							...newUserAcceptedBets,
							...response.userAcceptedBets
						];
						newUserContactCenterData = [
							...newUserContactCenterData,
							...response.userThreads
						];
					}

					$store.commit(
		        		`personalRoom/setUserData`,
		        		{
		        			moduleName: 'transactions',
		        			newUserData: newUserTransactionsData
		        		}
		    		);

		    		$store.commit(
		        		`personalRoom/setUserData`,
		        		{
		        			moduleName: 'createdBets',
		        			newUserData: newUserCreatedBets
		        		}
		    		);

		    		$store.commit(
		        		`personalRoom/setUserData`,
		        		{
		        			moduleName: 'acceptedBets',
		        			newUserData: newUserAcceptedBets
		        		}
		    		);

		    		$store.commit(
		        		`personalRoom/setUserData`,
		        		{
		        			moduleName: 'contactCenter',
		        			newUserData: newUserContactCenterData
		        		}
		    		);
				});



				// Итеративное заполнение тестовыми данными.

			    [
			    	//'transactions',
			    	//'acceptedBets',
			    	//'createdBets',
			    	//'contactCenter'
			    ].forEach(
			      	moduleName => {
			        	// First.
			        	$store.commit(
			        		`personalRoom/setUserData`,
			        		{moduleName}
			    		);
			      	}
			    );


				context.isLoadedData = true;
			});
		}, 2499);
	}">
	<!-- begin personalRoomUserPanel -->

    <div :class="{
    	'shadow_spread personalRoomUserPanel sticky position_base height_fill background-color_white index_big': true
	}">
      <!-- begin userInfo -->
      <div class="userInfo parent column centered relative margin_centered">
        <base-button :action="switchView(viewNames.contactCenter)" :class="`userInfoMessages absolute`" label="Перейти к сообщениям" unstyled>
          <icon :class="`userInfoMessages__icon ${personalRoom.currentOpenedView === viewNames.contactCenter ? ' userInfoMessages__icon_blue' : ''}`" name="envelope"></icon>
          <span class="absolute userInfoMessages__quantity parent centered circle padding_container-small background-color_blue color_white">
          	[[ user.contactCenter.length ]]
          </span>
        </base-button>
        <lazy-image :src="avatar.files.length ? avatar.files[0].url : user.avatar" :fit-block="false" class-name="userInfoAvatar overflow_hidden circle parent centered relative">
       		<file-upload ref="uploadAvatar" extensions="gif,jpg,jpeg,png,webp" accept="image/png,image/gif,image/jpeg,image/webp" name="avatar" class="absolute_force position_base userInfoAvatar__button width_fill height_fill parent_force centered color_white font-size_12" v-model="avatar.files" @input-filter="avatarFilter" @input-file="uploadUserAvatar">
          		Изменить аватар
        	</file-upload>
       	</lazy-image>
    	<!-- begin userInfo__title -->
        <div class="margin-top_base parent row h-between v-centered margin-top_zero-tabletLess">
	        <h2 class="userInfo__title font-size_18 font-weight_semibold">
	          [[ user.name ]]
	        </h2>
	        <base-button :action="() => { $store.dispatch('authorization/logOutAndCleanCache'); }" modifier="blue" class-name="circle color_white font-size_10" label="Выйти из аккаунта" is-circle>
	    		<svg aria-hidden="true" role="img" width="1em" height="1em" viewBox="0 0 512 512">
	    			<path fill="currentColor" d="M497 273L329 441c-15 15-41 4.5-41-17v-96H152c-13.3 0-24-10.7-24-24v-96c0-13.3 10.7-24 24-24h136V88c0-21.4 25.9-32 41-17l168 168c9.3 9.4 9.3 24.6 0 34zM192 436v-40c0-6.6-5.4-12-12-12H96c-17.7 0-32-14.3-32-32V160c0-17.7 14.3-32 32-32h84c6.6 0 12-5.4 12-12V76c0-6.6-5.4-12-12-12H96c-53 0-96 43-96 96v192c0 53 43 96 96 96h84c6.6 0 12-5.4 12-12z"></path>
	    		</svg>
	    	</base-button>
        </div>
        <!-- end userInfo__title -->
    	<!-- begin userInfo__balance -->
        <p class="userInfo__balance color_blue font-size_20 font-weight_bold margin-top_small margin-top_zero-tabletLess">
          $ [[ user.balance ]]
        </p>
        <!-- end userInfo__balance -->
      </div>
      <!-- end userInfo -->


      <nav class="personalRoomNavigation sticky index_big" aria-label="Навигация по личному кабинету">
        <ul class="personalRoomNavigationList font-size_14 height_fill v-end">
          <li  class="personalRoomSectionLink parent row margin-top_zero-tabletLess">

            <a class="personalRoomSectionLink__button parent row v-centered font-weight_semibold font-size_14 width_fill <?if(Yii::$app->controller->action->id=='index'){?>personalRoomSectionLink__button_active<?}?>" href="<?=Url::to(['user/index'])?>">
              <lazy-image src="/img/wallet.png"  class-name="personalRoomSectionLink__icon background-color_<?if(Yii::$app->controller->action->id=='index'){?>blue<?} else{?>darkBlue<?}?>"></lazy-image>

              <span class="personalRoomSectionLink__label color_darkBlue">
              Баланс
              </span>
            </a>
          </li>
          <li  class="personalRoomSectionLink parent row margin-top_zero-tabletLess">

            <a class="personalRoomSectionLink__button parent row v-centered font-weight_semibold font-size_14 width_fill <?if(Yii::$app->controller->action->id=='orders'){?>personalRoomSectionLink__button_active<?}?>" href="<?=Url::to(['user/orders'])?>">
              <lazy-image src="/img/createdBets.png"  class-name="personalRoomSectionLink__icon background-color_<?if(Yii::$app->controller->action->id=='orders'){?>blue<?} else{?>darkBlue<?}?>"></lazy-image>

              <span class="personalRoomSectionLink__label color_darkBlue">
              Созданные ставки
              </span>
            </a>
          </li>
          <li  class="personalRoomSectionLink parent row margin-top_zero-tabletLess">

            <a class="personalRoomSectionLink__button parent row v-centered font-weight_semibold font-size_14 width_fill <?if(Yii::$app->controller->action->id=='pays'){?>personalRoomSectionLink__button_active<?}?>" href="<?=Url::to(['user/pays'])?>">
              <lazy-image src="/img/acceptedBets.png"  class-name="personalRoomSectionLink__icon background-color_<?if(Yii::$app->controller->action->id=='pays'){?>blue<?} else{?>darkBlue<?}?>"></lazy-image>

              <span class="personalRoomSectionLink__label color_darkBlue">
              Принятые ставки
              </span>
            </a>
          </li>
          <li  class="personalRoomSectionLink parent row margin-top_zero-tabletLess">

            <a class="personalRoomSectionLink__button parent row v-centered font-weight_semibold font-size_14 width_fill <?if(Yii::$app->controller->action->id=='contact'){?>personalRoomSectionLink__button_active<?}?>" href="<?=Url::to(['user/contact'])?>">
              <lazy-image src="/img/envelope.png"  class-name="personalRoomSectionLink__icon background-color_<?if(Yii::$app->controller->action->id=='contact'){?>blue<?} else{?>darkBlue<?}?>"></lazy-image>

              <span class="personalRoomSectionLink__label color_darkBlue">
              Контакт центр
              </span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <!-- end personalRoomUserPanel -->

    <!-- begin personalRoom -->
    <div class="grow personalRoom relative height_fill">
    	<transition name="switchFading" appear>
	      <!-- begin personalRoomBalance -->
	      <section v-if="personalRoom.currentOpenedView === viewNames.balance && user.transactions.length" class="personalRoomBalance display_grid grid-rows_fill height_fill">

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
	            Депозит
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
	            Вывод
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
	            <h2 class="font-size_16 font-weight_semibold sorterContainer__title headingOffset_tablet">Транзакции:</h2>
	            <!-- begin transactionsSorter -->
	            <form class="transactionsSorter display_grid grow margin-top_small-tabletLess grid_three-phoneUp" action="" method="get">

	              <sorter-date until-date-name="transactionsUntilDate" since-date-name="transactionsSinceDate" styled @set-until-date="transactions.untilDate = $event;sortTransactions();" @set-since-date="transactions.sinceDate = $event;sortTransactions();"></sorter-date>

	              <sorter-types :is-shown="personalRoom[transactionBetTypeSorterName].isListOpened" :sorter-types="personalRoom[transactionBetTypeSorterName].types" :on-choose-sorter-type="switchSorterTypeAndSort({
											sorterTypesName: transactionBetTypeSorterName,
											sortFunctionName: 'sortTransactions'
									})" :switch-display-of-sorter-types="switchSorterTypesDisplayState(transactionBetTypeSorterName)" :current-sorter-type="personalRoom[transactionBetTypeSorterName].currentSorterType" :z-index="3" list-label="Список вариантов сортировки транзакций по ставкам" styled></sorter-types>

	              <sorter-types :is-shown="personalRoom[transactionActionTypeSorterName].isListOpened" :sorter-types="personalRoom[transactionActionTypeSorterName].types" :on-choose-sorter-type="switchSorterTypeAndSort({
											sorterTypesName: transactionActionTypeSorterName,
											sortFunctionName: 'sortTransactions'
									})" :switch-display-of-sorter-types="switchSorterTypesDisplayState(transactionActionTypeSorterName)" :current-sorter-type="personalRoom[transactionActionTypeSorterName].currentSorterType" :z-index="2" list-label="Список вариантов сортировки транзакций по ставкам" styled></sorter-types>

	              <sorter-types :is-shown="personalRoom[transactionStatusSorterName].isListOpened" :sorter-types="personalRoom[transactionStatusSorterName].types" :on-choose-sorter-type="switchSorterTypeAndSort({
											sorterTypesName: transactionStatusSorterName,
											sortFunctionName: 'sortTransactions'
									})" :switch-display-of-sorter-types="switchSorterTypesDisplayState(transactionStatusSorterName)" :current-sorter-type="personalRoom[transactionStatusSorterName].currentSorterType" :z-index="1" list-label="Список вариантов сортировки транзакций по ставкам" styled></sorter-types>

	            </form>
	            <!-- end transactionsSorter -->
	          </article>
	          <!-- end transactionSorterContainer  -->
	          <!-- begin someBetsTable -->

	          <article role="table" aria-label="Транзакции пользователя" class="someBetsTable background-color_white" aria-describedby="transactionsTable_description">
	            <h2 id="transactionsTable_description" class="visible-hidden">
	              Таблица транзакция пользователя
	            </h2>
	          <!-- begin userTransactionsHead -->
	          <user-transactions-header></user-transactions-header>
	          <!-- end userTransactionsHead -->

	          <!-- begin userTransactionsBody -->
	          <fade-translate-transition-group :delay="50" role="tablerows" tag="div" class="userTransactionsBody v-centered display_grid padding-top_quarter">

	            <user-transaction v-for="(transaction, index) in getRangeOfData($store.getters['personalRoom/transactionsByTimeDecreasing'], 'transactions')" :key="index" :data-index="index" :transaction-date="transaction.date" :user-action="transaction.action" :transaction-status="transaction.status.processed" :sum="transaction.sum" :commission="transaction.commission" :rest="transaction.rest"></user-transaction>

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


    <!-- end personalRoom -->



</personal-room>
<?php if (Yii::$app->request->get('_pjax')): ?>
  <script type="text/javascript">

    window.ComponentsHandler.initSection('personalRoom');

  </script>
<?php endif; ?>
