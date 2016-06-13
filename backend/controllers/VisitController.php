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
     * IIs访问详情列表
     */
    public function actionIislist() {
        return $this->render('iislist');
    }

    /**
     * nginx访问详情列表
     */
    public function actionNginxlist() {
        return $this->render('nginxlist');
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
                        'actions' => [ 'index', 'servicestatus', 'city', 'showtotal', 'showmap', 'api', 'iisvisit', 'nginxlist','iislist'],
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
    public function actionIisvisit() {
        return $this->render('iisvisit');
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

    /**
     * 显示地图
     */
    public function actionShowmap() {
        return $this->render('showmap');
    }

    /**
     * 处理地图信息
     */
    public function actionApi() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //获得调用的方法
        $fc = \Yii::$app->request->get('fc');
        if (!empty($fc)) {
            switch ($fc) {
                case 'twodayfit':
                    $dataLists = \backend\services\ZabbixHightchartService::fitTwoDay();
                    return $dataLists;
                case 'detail':
                    $dataLists = \backend\services\ZabbixHightchartService::fitDetailData();
                    return $dataLists;
                default :
            }
        }
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
        if (strtotime($eDate) > time()) {
            $eDate = date('Y-m-d H:i:s');
        }
//获得相应数据
        $datlists = \backend\services\ZabbixHightchartService::findSelectColumnFit($id, $sDate, $eDate);
        return $datlists;
    }

}