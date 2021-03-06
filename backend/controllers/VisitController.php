<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
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
                        'actions' => [ 'index', 'servicestatus', 'city', 'showtotal', 'showmap', 'api', 'iisvisit', 'nginxlist', 'iislist', 'nginxblock', 'onedtail'],
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
     * 对一组数据做统计，
     * 目前未打算做整合，只展示一个，后期看情况
     * @return type
     */
    public function actionOnedtail() {
        $fc = \Yii::$app->request->get("fc");
        switch ($fc) {
            case 'totalvisit':
                return $this->render('onedtail');
                break;
            case 'latevisit':
                return $this->render('latevisit');
                break;
            default :
                return $this->render('onedtail');
        }
    }

    /**
     * NGINX其他数据统计入口的BLOCK
     * @return type
     */
    public function actionNginxblock() {
        return $this->render('nginxblock');
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
                case 'chinamap':
                    $dataLists = \backend\services\NginxHightchartService::fitChinaMap();
                    return $dataLists;
                case 'worldmap':
                    $dataLists = \backend\services\NginxHightchartService::fitWorldMap();
                    return $dataLists;
                case 'plat_brower':
                    $dataLists = \backend\services\NginxHightchartService::fitPlatBrower();
                    return $dataLists;
                case 'errorstatus':
                    $dataLists = \backend\services\NginxHightchartService::fitErrors();
                    return $dataLists;
                case 'mobilebrower':
                    $dataLists = \backend\services\NginxHightchartService::fitMobilebrower();
                    return $dataLists;
                case 'totalvisit':
                    $dataLists = \backend\services\NginxHightchartService::fitTotalVisit();
                    return $dataLists;
                case 'latevisit':
                    $dataLists = \backend\services\NginxHightchartService::fitLateVisit();
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