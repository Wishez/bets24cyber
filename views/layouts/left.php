<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->avatar ?>" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->username ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
<?/*
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->
*/?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [
                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'Настройки сайта', 'icon' => 'fa fa-gears', 'url' => ['/admin/settings/index']],
                    ['label' => 'Ордеры', 'icon' => 'fa fa-money', 'url' => ['/admin/orders/index']],
                    ['label' => 'Фонды', 'icon' => 'glyphicon glyphicon-briefcase', 'url' => ['/admin/funds/index']],
                    ['label' => 'Валюты', 'icon' => 'glyphicon glyphicon-usd', 'url' => ['/admin/currency/index']],
                    ['label' => 'Поля для заполнения', 'icon' => 'glyphicon glyphicon-book', 'url' => ['/admin/user-fields/index']],
                    ['label' => 'Пользователи', 'icon' => 'fa fa-user', 'url' => ['/admin/user/index']],
                    ['label' => 'Транзакции', 'icon' => 'fa fa-book', 'url' => ['/admin/transaction/index']],
                    ['label' => 'Турниры', 'icon' => 'fa fa-book', 'url' => ['/admin/leagues/index']],
                    ['label' => 'Языки', 'icon' => 'fa fa-language', 'url' => ['/admin/langs/index']]
                ],
            ]
        ) ?>

    </section>

</aside>
