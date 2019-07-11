<?php

namespace app\controllers;

use \Exception;
use yii\widgets\ActiveForm;
use yii\web\Response;
use app\models\Orders;
use app\models\PayOrder;

use app\models\DotaGame;
use app\models\League;
use app\models\LoadTeam;
use app\models\Match;
use app\models\Settings;

use app\models\ResetPasswordForm;
use app\models\SendEmailForm;
use app\models\SignupForm;
use app\models\StaticPage;

use app\models\User;
use app\models\DotaHelper;
use nodge\eauth\openid\ControllerBehavior;
use app\models\TwitchLeagues;
use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class AdminController extends Controller
{
    public function actionIndex()
    {
        return $this->redirect(['admin/settings/index']);
    }
}
