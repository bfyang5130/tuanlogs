<?php

namespace backend\controllers;

use backend\services\AccessLogService;
use backend\services\IpLocationService;
use backend\services\ToolService;
use Composer\Package\Loader\ValidatingArrayLoader;
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
                        'actions' => ['nginx-access-file'],
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
     * 分析nginx配置文件
     * @return type
     */
    public function actionNginxAccessFile(){
        header("Content-type: text/html; charset=utf-8");
        set_time_limit(0) ;
        ini_set('memory_limit','4024M');
        $save_rs = false ;

        $dir = Yii::getAlias("@backend")."/resource17" ;
        $handle = dir($dir) ;
        $source = "17" ;

        while($entry = $handle->read()) {
            if (!in_array($entry, array('.', '..'))) {
                $file_url = $dir . "/" . $entry;

                //获取文件名
                $short_name = ToolService::parseFileName($entry) ;
                //判断是否用cdn格式
                $isCdn = ToolService::isCdn($short_name) ;

                $cur_date = date("Y-m-d") ;
                $deal_date = Yii::$app->cache->get("deal_date") ;

                //日期不一致时,删除上次读到的最后一行,
                //为隔天时,可以从第一行读取
                $end_num_cache_name = "end_num".$entry ;
                if($deal_date!=$cur_date){
                    Yii::$app->cache->delete($end_num_cache_name);
                }
                //读取上次读到的最后一行
                $last_end_num = empty(Yii::$app->cache->get($end_num_cache_name))?0:Yii::$app->cache->get($end_num_cache_name) ;

                $total_line = ToolService::count_line($file_url) ;

                $start_num = $last_end_num +1 ;
                $end_num =  $total_line;
                $content_arr = ToolService::getFileLines($file_url,$start_num,$end_num) ;
                $save_rs = AccessLogService::analyForNginx($content_arr,$isCdn,$short_name,null) ;
                unset($content_arr) ;

                //记录读到的最后一行
                Yii::$app->cache->set($end_num_cache_name,$total_line) ;
                //记录日期
                Yii::$app->cache->set("deal_date",date("Y-m-d")) ;

                if($save_rs==true) {
                    //处理完后删除文件,防范重复入库
//                  @unlink($file_url);
                }
            }
        }

        if(empty($file_url)){
            $msg = $dir." 目录下没有可读取的文件" ;
        }else{
            $msg = "处理成功" ;
        }
        Yii::$app->getSession()->setFlash('message', $msg);
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