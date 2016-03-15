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
                        'actions' => ['nginx-access-file','test-demo'],
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
    public function actionNginxAccessFile($message = '17', $fitdata = '') {
        header("Content-type: text/html; charset=utf-8");
        set_time_limit(0);
        ini_set('memory_limit', '4024M');
        $save_rs = false;
        //如果有传入时间参数那么以时间参数为准 
        if (empty($fitdata)) {
            $fitdata = date("Ymd", time());
        }
        if ($message == '17') {
            $dir = \Yii::$app->params['proxy17'];
            $source = "17";
        } else {
            $dir = \Yii::$app->params['proxy21'];
            $source = "21";
        }
        $dir.='/'.$fitdata;
        $dir = Yii::getAlias("@backend")."/resource17" ;
        $handle = dir($dir);

        while ($entry = $handle->read()) {
            if (!in_array($entry, array('.', '..'))) {
                $file_url = $dir . "/" . $entry;

                //获取文件名
                $short_name = ToolService::parseFileName($entry);
                //判断是否用cdn格式
                $isCdn = ToolService::isCdn($short_name);

                $cur_date = date("Y-m-d");
                $deal_date = Yii::$app->cache->get("deal_date");

                //日期不一致时,删除上次读到的最后一行,
                //为隔天时,可以从第一行读取
                $end_num_cache_name = "end_num" . $entry;
                if ($deal_date != $cur_date) {
                    Yii::$app->cache->delete($end_num_cache_name);
                }
                Yii::$app->cache->delete($end_num_cache_name);
                //读取上次读到的最后一行
                $last_end_num = empty(Yii::$app->cache->get($end_num_cache_name)) ? 0 : Yii::$app->cache->get($end_num_cache_name);

                $total_line = ToolService::count_line($file_url);

                $start_num = $last_end_num + 1;
                $end_num = $total_line;
                $content_arr = ToolService::getFileLines($file_url, $start_num, $end_num);
                $save_rs = AccessLogService::analyForNginx($content_arr, $isCdn, $short_name, '17');
                unset($content_arr);

                //记录读到的最后一行
                Yii::$app->cache->set($end_num_cache_name, $total_line);
                //记录日期
                Yii::$app->cache->set("deal_date", date("Y-m-d"));

                if ($save_rs == true) {
                    //处理完后删除文件,防范重复入库
//                  @unlink($file_url);
                }
            }
        }

        if (empty($file_url)) {
            $msg = $dir . " 目录下没有可读取的文件";
        } else {
            $msg = "处理成功";
        }
        Yii::$app->getSession()->setFlash('message', $msg);
        return $this->redirect(['site/tip']);
    }

    /**
     * 读取iis配置文件
     * @return type
     */
    public function actionIisAccessFile() {
        $sorce_file = "/resource/iis_log.log";
        $file_url = Yii::getAlias("@backend") . $sorce_file;
        $content = file_get_contents($file_url);
        $content_arr = explode("\n", $content);
        $save_rs = AccessLogService::saveToDbForIis($content_arr);
        if ($save_rs == true) {
            //处理完后删除文件,防范重复入库
//        @unlink($file_url);
        }
        Yii::$app->getSession()->setFlash('message', '处理成功');
        return $this->redirect(['site/tip']);
    }

    public function actionTestDemo(){
//        $request_type["POST"] = 10 ;
//        $request_type["GET"] = 2 ;
//        AccessLogService::countRequest($request_type,21,"app.tuandai.com") ;

        header("Content-type: text/html; charset=utf-8");
        set_time_limit(0);
        ini_set('memory_limit', '4024M');
        $save_rs = false;
        //如果有传入时间参数那么以时间参数为准
        if (empty($fitdata)) {
            $fitdata = date("Ymd", time());
        }
        $source = 17 ;
        $step = FALSE;
        $dir = Yii::getAlias("@backend")."/resource17" ;
        $handle = dir($dir);

        while ($entry = $handle->read()) {
            if (!in_array($entry, array('.', '..'))) {
                $file_url = $dir . "/" . $entry;
                //获取文件名22
                $short_name = ToolService::parseFileName($entry);
                //判断是否用cdn格式
                $isCdn = ToolService::isCdn($short_name);

                $cur_date = $fitdata;
                $deal_date = Yii::$app->cache->get($source."deal_date".$entry);

                //日期不一致时,删除上次读到的最后一行,
                //为隔天时,可以从第一行读取
                $end_num_cache_name = "end_num" . $entry;
                if ($deal_date != $cur_date) {
                    Yii::$app->cache->delete($end_num_cache_name);
                    Yii::$app->cache->set($source."deal_date".$entry, $cur_date);
                }
                Yii::$app->cache->delete($end_num_cache_name);
                Yii::$app->cache->set($source."deal_date".$entry, $cur_date);
                echo $end_num_cache_name;
                echo "\n";
                //读取上次读到 的最后一行
                $last_end_num = empty(Yii::$app->cache->get($end_num_cache_name)) ? 0 : Yii::$app->cache->get($end_num_cache_name);
                echo 'filename:';
                echo "\n";
                echo $file_url;
                echo 'thisRemark';
                echo $last_end_num;
                echo "\n";
                $total_line = ToolService::count_line($file_url);
                $start_num = $last_end_num + 1;
                $end_num = $total_line;
                $save_rs = [];
                //一次处理500条数据
                while ($start_num < $end_num) {
                    //设定要处理的终结行
                    $fit_endNum = $start_num + 1000;
                    //是否是最后一条数据的处理
                    $endDateNumFit = false;
                    if ($fit_endNum >= $end_num) {
                        $fit_endNum = $end_num;
                        $endDateNumFit = true;
                    }
                    //开始处理行数
                    $content_arr = ToolService::getFileLines($file_url, $start_num, $fit_endNum);
                    //$save_rs是前一次处理留下来的数据。这里做一下判断处理
                    $st_check_t = 0; //上次的检查时间
                    $preA = []; //上次处理留下的数据
                    if (!empty($save_rs)) {
                        $st_check_t = $save_rs['str_check_time'];
                        $preA = $save_rs['leaveDate'];
                    }
                    $save_rs = AccessLogService::analyForNginx($content_arr, $isCdn, $short_name, $source, $endDateNumFit, $st_check_t, $preA, $start_num, $end_num_cache_name,$step);
                    $start_num = $fit_endNum;
                }
                unset($content_arr);

                //记录读到的最后一行
                Yii::$app->cache->set($end_num_cache_name, $total_line);
                //记录日期
                Yii::$app->cache->set($source."deal_date".$entry, $cur_date);

                if (empty($save_rs)) {
                    //处理完后删除文件,防范重复入库
//                  @unlink($file_url);
                }
            }
        }

        if (empty($file_url)) {
            $msg = $dir . " 目录下没有可读取的文件";
        } else {
            $msg = "处理成功";
        }

        if (empty($file_url)) {
            $msg = $dir . " 目录下没有可读取的文件";
        } else {
            $msg = "处理成功";
        }
    }

}