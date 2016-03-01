<?php

namespace console\controllers;

use backend\services\AccessLogService;
use backend\services\DistributeLogService;
use backend\services\ToolService;
use Faker\Provider\Base;
use Yii;
use yii\console\Controller;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\filters\VerbFilter;

/**
 * 日志处理后台程序
 */
class LogDealController extends Controller {


    //后台分发程序,直接在后台运行
    public function actionBackDistribute(){
        $save_rs = false ;
        $sorce_file = "/resource/access.log" ;
        $short_name = ToolService::parseFileName("access.log") ;
        $file_url = Yii::getAlias("@backend").$sorce_file ;
        $total_line = ToolService::count_line($file_url) ;
        $dist_num = ceil($total_line/ToolService::DISTRIBUTE_NUM) ;
        $source = "23" ;
        for($i=0;$i<$dist_num;$i++){
            $str_num = $i*ToolService::DISTRIBUTE_NUM + 1;
            //阻塞
//            exec("/home/wuxin/web/tuanlogs/yii demo/myfunc $str_num&") ;
            //异步,非阻塞,放在后台运行
            $out = popen("/home/wuxin/web/tuanlogs/yii log-deal/front-distribute $str_num $file_url $short_name $source $total_line &", "r");
        }
        pclose($out);
    }

    //供前台调用
    public function actionFrontDistribute($str_num,$file_url,$short_name,$source,$total_line){
        ini_set('memory_limit','1024M');
        $isCdn = ToolService::isCdn($short_name) ;
        $total_page = ceil(ToolService::DISTRIBUTE_NUM/ToolService::READ_LINE) ;
        $end_num = $str_num + ToolService::DISTRIBUTE_NUM - 1;
        if($end_num>=$total_line){
            $end_num = $total_line ;
        }
        $id = DistributeLogService::saveToDb($str_num,$end_num,$short_name,$file_url,$source) ;
        for($i=0;$i<$total_page;$i++){
            $start_num = $i*ToolService::READ_LINE + $str_num ;
            $end_num = $start_num + ToolService::READ_LINE - 1 ;
            $content_arr = ToolService::getFileLines($file_url,$start_num,$end_num) ;
            AccessLogService::saveToDbForNginx($content_arr,$isCdn,$short_name,$source) ;
            unset($content_arr) ;
        }
        DistributeLogService::updateToDb($id) ;
    }




}