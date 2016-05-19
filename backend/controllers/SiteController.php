<?php

namespace backend\controllers;

use backend\models\ErrorLogSearch;
use backend\services\ErrorLogService;
use backend\services\TraceLogService;
use backend\services\ToolService;
use common\models\User;
use Yii;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'tip', 'tongji'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        //echo ToolService::convertip_tiny('121.13.249.210','K:/web/tuanlogs/common/data/tinyipdata.dat');exit;
        return $this->render('index');
    }

    public function actionTongji() {
        //echo ToolService::convertip_tiny('121.13.249.210','K:/web/tuanlogs/common/data/tinyipdata.dat');exit;
        return $this->render('tongji');
    }

    /**
     * 登录入口
     * @return type
     */
    public function actionLogin() {
        $this->layout = 'login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    //提示页面
    public function actionTip() {
        $message = empty(Yii::$app->getSession()->getFlash('message')) ? "" : Yii::$app->getSession()->getFlash('message');
        return $this->render('tip', ["message" => $message]);
    }

}
