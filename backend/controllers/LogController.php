<?php

namespace backend\controllers;

use backend\services\AccessLogService;
use backend\services\ToolService;
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
                        'actions' => ['nginx-access-file','iis-access-file','dist-one','dist-two'],
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
     * 分发17机子
     */
    public function actionDistOne(){
        //17机子的目录
        $dir = Yii::getAlias("@backend")."/resource17" ;
        $handle = dir($dir) ;
        $source = "17" ;
        while($entry = $handle->read()) {
            if(!in_array($entry, array('.', '..'))){
                $file_url = $dir."/".$entry ;
                $total_line = ToolService::count_line($file_url) ;
                $short_name = ToolService::parseFileName($entry) ;
                $dist_num = ceil($total_line/ToolService::DISTRIBUTE_NUM) ;
                for($i=0;$i<$dist_num;$i++){
                    $str_num = $i*ToolService::DISTRIBUTE_NUM + 1;
                    $out = popen("../../yii log-deal/front-distribute $str_num $file_url $short_name $source $total_line &", "r");
                    pclose($out);
                }
            }
        }
        if(empty($file_url)){
            $msg = $dir." 目录下没有可读取的文件" ;
        }else{
            $msg = "分发成功,请等待结果" ;
        }
        Yii::$app->getSession()->setFlash('message', $msg);
        return $this->redirect(['/site/tip']) ;
    }

    /**
     * 分发23机子
     */
    public function actionDistTwo(){
        //23机子的目录
        $dir = Yii::getAlias("@backend")."/resource23" ;
        $handle = dir($dir) ;
        $source = "23" ;
        while($entry = $handle->read()) {
            if(!in_array($entry, array('.', '..'))){
                $file_url = $dir."/".$entry ;
                $total_line = ToolService::count_line($file_url) ;
                $short_name = ToolService::parseFileName($entry) ;
                $dist_num = ceil($total_line/ToolService::DISTRIBUTE_NUM) ;
                for($i=0;$i<$dist_num;$i++){
                    $str_num = $i*ToolService::DISTRIBUTE_NUM + 1;
                    $out = popen("../../yii log-deal/front-distribute $str_num $file_url $short_name $source $total_line &", "r");
                    pclose($out);
                }
            }
        }
        if(empty($file_url)){
            $msg = $dir." 目录下没有可读取的文件" ;
        }else{
            $msg = "分发成功,请等待结果" ;
        }
        Yii::$app->getSession()->setFlash('message', $msg);
        return $this->redirect(['/site/tip']) ;
    }


    /**
     * 读取nginx配置文件
     * @return type
     */
    public function actionNginxAccessFile(){
        set_time_limit(0) ;
        ini_set('memory_limit','1024M');
        $save_rs = false ;
        $sorce_file = "/resource/vip.tuandai.com.access.log" ;
        $file_url = Yii::getAlias("@backend").$sorce_file ;
        $total_line = ToolService::count_line($file_url) ;
        $total_page = ceil($total_line/ToolService::READ_LINE) ;
        for($i=0;$i<$total_page;$i++){
            $start_num = $i*ToolService::READ_LINE+1 ;
            $end_num = $i*ToolService::READ_LINE + ToolService::READ_LINE ;
            $content_arr = ToolService::getFileLines($file_url,$start_num,$end_num) ;
            $save_rs = AccessLogService::saveToDbForNginx($content_arr) ;
            unset($content_arr) ;
        }
        if($save_rs==true) {
            //处理完后删除文件,防范重复入库
//        @unlink($file_url);
        }
        Yii::$app->getSession()->setFlash('message', '处理成功');
        return $this->redirect(['site/tip']) ;
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
        Yii::$app->getSession()->setFlash('message', '处理成功');
        return $this->redirect(['site/tip']) ;
    }



}