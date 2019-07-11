<?php

namespace app\controllers\admin;

use app\models\ButtonCap;
use app\models\League;
use app\models\Match;
use app\models\News;
use app\models\Settings;
use app\models\TopStreams;
use app\models\UploadForm;
use vova07\imperavi\actions\GetAction;
use vova07\imperavi\helpers\FileHelper;
use app\widgets\imgUpload\actions\GetImages;
use Yii;
use yii\base\ErrorException;
use yii\imagine\Image;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\HttpException;
use yii\web\UploadedFile;

class SettingsController extends \yii\web\Controller
{
    public $layout = "admin";

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::classname(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],

                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'img-upload' => [
                'class' => 'app\widgets\imgUpload\actions\UploadAction',
                'url' => '/uploads/images', // Directory URL address, where files are stored.
                'path' => '@app/web/uploads/images' // Or absolute path to directory where files are stored.
            ],
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadAction',
                'url' => '/uploads/images', // Directory URL address, where files are stored.
                'path' => '@app/web/uploads/images' // Or absolute path to directory where files are stored.
            ],
            'images-get' => [
                'class' => 'vova07\imperavi\actions\GetAction',
                'url' => '/uploads/images', // Directory URL address, where files are stored.
                'path' => '@app/web/uploads/images', // Or absolute path to directory where files are stored.
                'type' => GetAction::TYPE_IMAGES,
            ]
        ];
    }

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $logo = new UploadForm();
        $header_menu = Settings::findOne('header_menu');
        $model_button_cap= ButtonCap::find()->where('id_bc=1')->one();


        if ($header_menu->options) {
            $header_menu->header_menu = unserialize($header_menu->options);
        } else {
//            throw new HttpException(400, 'Bad Request');
        }
        // var_dump($request->post('settings'));
        // exit();
        if ($request->isPost) {
            if ($request->post('settings')) {
                Settings::setValues($request->post('settings'));
            }
            if ($header_menu->load($request->post()) && $header_menu->save()) {
                return $this->redirect('settings');
            }

            if ($var=(Yii::$app->request->post('ButtonCap'))) {
                $model_button_cap->link=$var['link'];
                $model_button_cap->save(false);
            }
        }


        //$model_match = Match::getModel_match()->all();
        //$model_league= League::find()->where('visible=1')->all();
        $model_top_streams= TopStreams::find()->where('visible=1')->all();
        $model_news= News::find()->where('show_in_footer=1')->all();
        //$model_bookmaker= Match::find()->where('flag_best_bookmaker=1')->all();
        $model_league = League::find()->orderBy('date_start DESC')->asArray()->all();
        $model_match = Match::find()->orderBy('date')->asArray()->all();
        $settings = Settings::getValues();
        $timezones = Settings::getTimezones();
        return $this->render('index', [
                                        'logo' => $logo,
                                        'header_menu' => $header_menu,
                                        'model_top_streams'=>$model_top_streams,
                                        'model_news'=>$model_news,
                                        'model_button_cap'=>$model_button_cap,
                                        'model_league' => $model_league,
                                        'model_match' => $model_match,
                                        'settings' => $settings,
                                        'timezones'=>$timezones
                                        ]);
    }

    public function actionDelInformation($id, $key)
    {
        if (($key=='match')and(($model = Match::findOne($id))!=null)) {
            Yii::$app->db->createCommand('DELETE FROM league_games WHERE id = '.$id)->execute();
        } elseif (($key=='league')and(($model = League::findOne($id)!=null))) {
            $name = League::find()->select('tournament_id as ids')->where('id = '.$id)->asArray()->one();
            if (!empty($name['ids'])) {
                Yii::$app->db->createCommand('DELETE FROM leagues WHERE id = '.$id)->execute();
                Yii::$app->db->createCommand('DELETE FROM league_games WHERE league_id = "'.$name['ids'].'"')->execute();
            }
        } elseif (($key=='streams')and(($model = TopStreams::findOne($id))!=null)) {
            $model['visible']=0;
            $model->save(false);
        } elseif (($key=='news')and(($model = News::findOne($id))!=null)) {
            $model['show_in_footer']=0;
            $model->save(false);
        } elseif (($key=='bookmaker')and(($model = Match::findOne($id))!=null)) {
            $model['flag_best_bookmaker']=0;
            $model->save(false);
        }

        return $this->redirect(['/admin/settings']);
    }


    public function actionUploadLogo()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $uploadForm = new UploadForm();
            $uploadForm->imageFile = UploadedFile::getInstance($uploadForm, 'imageFile');
//            var_dump($request);
            if ($uploadForm->uploadLogo()) {
                return "<img src='/uploads/Logo." . $uploadForm->imageFile->extension . "' width='100'/>";
            } else {
                return "<img src=''>";
            }
        }
        return "<img src=''>";
    }

    public function actionHeaderMenu()
    {
        $request = \Yii::$app->request;
        if ($request->isPost) {
            $header_menu = new Settings();

            if ($header_menu->load($request->post()) && $header_menu->save()) {
                return $this->redirect('index');
            }
            return $this->redirect('index');

//            $serialize = serialize($request->post('Settings')['header_menu']);

//            var_dump('<pre>',$serialize);
        }
        return $this->redirect('index');
    }

    public function actionImages()
    {
        $this->layout = false;
        $images = new \app\widgets\imgUpload\models\GetImages();
        return $this->render('@app/widgets/imgUpload/views/images', ['images' => $images->getImages("/uploads/images", '@app/web/uploads/images')]);
    }
}
