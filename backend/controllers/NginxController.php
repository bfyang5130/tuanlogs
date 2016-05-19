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
     * echarts api数据接口
     * @return type
     */
    public function actionApi() {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //获得调用的方法
        $fc = \Yii::$app->request->get('fc');
        if (!empty($fc)) {
            switch ($fc) {
                case 'pageattack':
                    $dataLists = \backend\services\NginxHightchartService::pageAttackEcharts('', [], 'databasetype');
                    return $dataLists;
                case 'findAllLine':
                    $dataLists = \backend\services\NginxHightchartService::findAllLineEcharts();
                    return $dataLists;
                default :
                    return [];
            }
        }
        return [];
    }

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
                        'actions' => [ 'index', 'city', 'errorstatus', 'sqlattack', 'api'],
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