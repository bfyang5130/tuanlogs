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
class LogdealController extends Controller {

    /**
     * 分析nginx配置文件
     * @return type
     */
    public function actionNginxaccessfile($message = '17', $fitdata = '') {
        //
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
        $dir.='/' . $fitdata;
        $handle = dir($dir);
        $fitFileArray = \Yii::$app->params['nginxfitfile'];
        while ($entry = $handle->read()) {
            if (!in_array($entry, array('.', '..'))) {
                $file_url = $dir . "/" . $entry;
                //获取文件名
                $short_name = ToolService::parseFileName($entry);
                if (!in_array($short_name, $fitFileArray)) {
                    continue;
                }
                //判断是否用cdn格式
                $isCdn = ToolService::isCdn($short_name);

                $cur_date = $fitdata;
                $deal_date = Yii::$app->cache->get("deal_date");

                //日期不一致时,删除上次读到的最后一行,
                //为隔天时,可以从第一行读取
                $end_num_cache_name = "end_num" . $entry;
                if ($deal_date != $cur_date) {
                    Yii::$app->cache->delete($end_num_cache_name);
                }
                Yii::$app->cache->delete($end_num_cache_name);
                //读取上次读到 的最后一行
                $last_end_num = empty(Yii::$app->cache->get($end_num_cache_name)) ? 0 : Yii::$app->cache->get($end_num_cache_name);

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
                    $save_rs = AccessLogService::analyForNginx($content_arr, $isCdn, $short_name, $source, $endDateNumFit, $st_check_t, $preA);
                    $start_num = $fit_endNum;
                }
                unset($content_arr);

                //记录读到的最后一行
                Yii::$app->cache->set($end_num_cache_name, $total_line);
                //记录日期
                Yii::$app->cache->set("deal_date", date("Y-m-d"));

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
        echo $msg;
    }

    //后台分发程序,直接在后台运行
    public function actionBackDistribute() {
        $save_rs = false;
        $sorce_file = "/resource/access.log";
        $short_name = ToolService::parseFileName("access.log");
        $file_url = Yii::getAlias("@backend") . $sorce_file;
        $total_line = ToolService::count_line($file_url);
        $dist_num = ceil($total_line / ToolService::DISTRIBUTE_NUM);
        $source = "23";
        for ($i = 0; $i < $dist_num; $i++) {
            $str_num = $i * ToolService::DISTRIBUTE_NUM + 1;
            //阻塞
//            exec("/home/wuxin/web/tuanlogs/yii demo/myfunc $str_num&") ;
            //异步,非阻塞,放在后台运行
            $out = popen("/home/wuxin/web/tuanlogs/yii log-deal/front-distribute $str_num $file_url $short_name $source $total_line &", "r");
        }
        pclose($out);
    }

    //供前台调用
    public function actionFrontDistribute($str_num, $file_url, $short_name, $source, $total_line) {
        ini_set('memory_limit', '1024M');
        $isCdn = ToolService::isCdn($short_name);
        $total_page = ceil(ToolService::DISTRIBUTE_NUM / ToolService::READ_LINE);
        $end_num = $str_num + ToolService::DISTRIBUTE_NUM - 1;
        if ($end_num >= $total_line) {
            $end_num = $total_line;
        }
        $id = DistributeLogService::saveToDb($str_num, $end_num, $short_name, $file_url, $source);
        for ($i = 0; $i < $total_page; $i++) {
            $start_num = $i * ToolService::READ_LINE + $str_num;
            $end_num = $start_num + ToolService::READ_LINE - 1;
            $content_arr = ToolService::getFileLines($file_url, $start_num, $end_num);
            AccessLogService::saveToDbForNginx($content_arr, $isCdn, $short_name, $source);
            unset($content_arr);
        }
        DistributeLogService::updateToDb($id);
    }

}