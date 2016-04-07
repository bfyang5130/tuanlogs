<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\forms\MonitorForm;
use \Yii;

/**
 * Site controller
 */
class ServerController extends Controller {

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
                        'actions' => [ 'index', 'addmonitor','selectmonitor'],
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
     * 加载服务器监控的首面
     */
    public function actionIndex() {
        return $this->render('index');
    }

    /**
     * 添加监控
     */
    public function actionAddmonitor() {

        $monitorForm = new MonitorForm();
        $fitForm = Yii::$app->request->post();
        $databaseFit = 0;
        if (isset($fitForm['MonitorForm'])) {
            $monitorForm->load(Yii::$app->request->post());

            if ($monitorForm->save()) {
                $databaseFit = 1;
            } else {
                $databaseFit = 2;
            }
        }
        return $this->render('addmonitor', [
                    'monitorForm' => $monitorForm,
                    'databaseFit' => $databaseFit,
                        ]
        );
    }

    /**
     * 增加对应的接口信息 
     */
    public function actionSelectmonitor() {
        return $this->render('selectmonitor');
    }

}