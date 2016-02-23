<?php

namespace backend\controllers;

use backend\services\AccessLogService;
use backend\services\ToolService;
use backend\services\UserAgentService;
use common\models\AccessLog;
use common\service\BaseToolService;
use Faker\Provider\Base;
use Yii;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * Log controller
 */
class LogController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['nginx-access-file','iis-access-file'],
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
     * 读取nginx配置文件
     * @return type
     */
    public function actionNginxAccessFile(){
        $sorce_file = "/resource/nginx_log.txt" ;
        $file_url = Yii::getAlias("@backend").$sorce_file ;
        $content = file_get_contents($file_url) ;
        $content_arr = explode("\n", $content);
        $save_rs = AccessLogService::saveToDbForNginx($content_arr) ;
        if($save_rs==true) {
            //处理完后删除文件,防范重复入库
//        @unlink($file_url);
        }
        echo "处理成功" ;
    }

    /**
     * 读取iis配置文件
     * @return type
     */
    public function actionIisAccessFile(){
        $sorce_file = "/resource/iis_log.log" ;
        $file_url = Yii::getAlias("@backend").$sorce_file ;
        $content = file_get_contents($file_url) ;
        $content_arr = explode("\n", $content);
        $save_rs = AccessLogService::saveToDbForIis($content_arr) ;
        if($save_rs==true) {
            //处理完后删除文件,防范重复入库
//        @unlink($file_url);
        }
        echo "处理成功" ;
    }



}