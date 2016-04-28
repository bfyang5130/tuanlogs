<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\models\forms\MonitorForm;
use \Yii;
use common\models\Monitor;

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
                        'actions' => [ 'index', 'addmonitor', 'selectmonitor', 'setindex', 'status', 'demo', 'api'],
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
     * 测试案例
     */
    public function actionDemo() {
        return $this->render('demo');
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

    /**
     * 处理为首页显示
     */
    public function actionSetindex() {
        $get = \Yii::$app->request->get();
        $result = FALSE;
        if (isset($get['isindex']) && $get['isindex']) {
            $is_indx = 0;
        } else {
            $is_indx = 1;
        }
        if (isset($get['id'])) {
            $result = Monitor::updateAll(['is_index' => $is_indx], 'id=:id', [':id' => $get['id']]);
        }

        if ($result) {
            $upstatus = TRUE;
        } else {
            $upstatus = FALSE;
        }
        return $this->renderAjax('setindex', ['upstatus' => $upstatus]);
    }

    public function actionApi() {

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        if (empty($id)) {
            return [];
        }
        //配置选择的时间
        $date = \Yii::$app->request->get('date');
        
        if (empty($date)) {
            $sDate = date('Y-m-d 00:00:00');
            $eDate = date('Y-m-d H:i:s');
        } else {
            $sDate = date('Y-m-d 00:00:00', strtotime($date));
            $eDate = date('Y-m-d 00:00:00', strtotime("+1 day", strtotime($date)));
        }
        if(strtotime($eDate)>  time()){
            $eDate=date('Y-m-d H:i:s');
        }
//获得相应数据
        $datlists = \backend\services\ZabbixHightchartService::findSelectColumnFit($id, $sDate, $eDate);
        return $datlists;
    }

}