<?php

namespace backend\controllers;

use backend\services\ErrorLogService;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class VisitController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [ 'index', 'servicestatus','city','showtotal'],
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

    /**
     * 显示nginx访问首页
     * @return type
     */
    public function actionIndex() {
        return $this->render('index');
    }
    /**
     * 显示nginx访问首页
     * @return type
     */
    public function actionShowtotal() {
        return $this->render('showtotal');
    }
    /**
     * 显示服务器状态
     */
    public function actionServicestatus() {
        return $this->render('servicestatus');
    }
/**
     * 显示服务器状态
     */
    public function actionCity() {
        return $this->render('city');
    }
}