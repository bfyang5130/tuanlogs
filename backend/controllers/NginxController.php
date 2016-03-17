<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class NginxController extends Controller {

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
                        'actions' => [ 'index', 'city', 'errorstatus','sqlattack'],
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
        return $this->render('index');
    }

    /**
     * 显示一个城市的具体数据
     * @return type
     */
    public function actionCity() {
        return $this->render('city');
    }

    /**
     * 查看某一天的链接错误信息
     */
    public function actionErrorstatus() {
        return $this->render('errorstatus');
    }
    /**
     * 查看sql攻击信息
     */
    public function actionSqlattack() {
        return $this->render('sqlattack');
    }

}