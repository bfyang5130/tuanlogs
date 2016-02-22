<?php

namespace backend\services;

use common\models\TraceLog;
use common\models\TraceLogMonth;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\models\TraceLogDay;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use yii\helpers\VarDumper;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppcationNameService
 *
 * @author Administrator
 */
class TraceLogService {

    public static $options;
    /**
     * 
     * @return \yii\data\ActiveDataProvider
     */
    public static function findTraceLogByAppId() {
        $p_get = \Yii::$app->request->get();
        if (!$p_get['id']) {
            $p_get['id'] = 1;
        }
        $model = new TraceLog();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where("ApplicationId=:appid", [":appid" => $p_get['id']]),
            'pagination' => [
                'pagesize' => 20,
            ]
        ]);
        return $dataProvider;
    }

    public function TraceGroupBy()
    {
        $data = (new \yii\db\Query())
            ->select("count(*) as total , ApplicationId")
            ->from('TraceLog')
            ->groupBy('ApplicationId')->all();
        return $data;
    }

    /**
     * 日统计
     * @return array
     * @throws \Exception
     */
    public static function CountDay()
    {
        //按照天数算的总记录

        #所有错误类别
        $category = [];
        foreach(self::getTraceCategory(false) as $k =>$v){
            $category[$v] = floatval(0);
        }
        $page = \Yii::$app->request->get('page',1);

        $start = date('Y-m-d' ,time() - 86400 * 5 * ($page - 1) + 86400) ;
        $end = date('Y-m-d',strtotime($start) - 86400 * 5 );

        if($page > 1){
            $TraceLogList = $trace_day_log  = TraceLogDay::find()
                ->select("ApplicationId , `Date` as dateline ,Number  ")
                ->where(['between','Date',date('Ymd',strtotime($end)),date('Ymd',strtotime($start-86400))])
                ->asArray()->all();
        }

        if(empty($TraceLogList)){
            $TraceLogList = TraceLog::find()
                ->select("count(*) as Number , ApplicationId ,date(`AddDate`) as `dateline`  ")
                ->where(['between','AddDate',$end,$start])
                ->groupBy("ApplicationId")
                ->asArray()->all();
        }

        #错误类型排序
        foreach($TraceLogList as $k => $v){
            $category[$v['ApplicationId']] += floatval($v['Number']);
        }
        arsort($category);
        $dataCategory = [];
        $series_categroy = [];
        foreach($category as $k => $v ){
            $dataCategory[trim($k)] = floatval(0);
            $series_categroy[] = $k;
        }

        $category = $dataCategory;
        $result = [];
        for($i=1;$i < 6;$i++){
            if(!empty($trace_day_log)){
                $result[date('Ymd',strtotime($start) - 86400 * $i)] = $category;
            }else{
                $result[date('Y-m-d',strtotime($start) - 86400 * $i)] = $category;
            }
        }

        foreach($TraceLogList as $k => $v){
            $result[$v['dateline']][trim($v['ApplicationId'])] +=  floatval($v['Number']);
        }

        #数据存入日统计表
        if(empty($trace_day_log) && $page > 1){
            $i = 0;
            foreach($result as $k => $v){
                $date = date('Ymd',strtotime($k));
                foreach($v as $kk => $vv){
                    $insertData[$i][] = $kk;
                    $insertData[$i][] = $vv;
                    $insertData[$i][] = $date;
                    $insertData[$i][] = date('Y-m-d h:i:s');
                    $i++;
                }
            }
            \Yii::$app->db->createCommand()
                ->batchInsert('TraceLog_day', ['ApplicationId','Number' , 'Date','Updatetime'],$insertData)
                ->execute();
        }

        $data = [];
        $i = 0;
        foreach($result as $k => $v){
            if(!empty($trace_day_log)){
                $data[$i]['name'] = substr($k,0,4).'-'.substr($k,3,2).substr($k,5,2);
            }else{
                $data[$i]['name'] = $k;
            }
            $data[$i]['data'] = array_values($v);
            $i++;
        }

        return [
            'type' => 'day',
            'category' => $series_categroy,
            'trace_series' => $data,
            'highcharts_title' => ''
        ];
    }

    /**
     * 月统计
     * @return array
     * @throws \Exception
     */
    public static function CountMon($optionDate = '')
    {
        self::options();//日期选项

        $category = self::getTraceCategory(false);//错误类型

        foreach($category as $k => $v){
            $category[trim($v)] = floatval(0);
            unset($category[$k]);
        }

        if(!empty($optionDate)){
            if(date('Y',strtotime($optionDate)) > date('Y')){
                $optionDate = '';
            }
            if(date('Y',strtotime($optionDate)) == date('Y')){
                if(date('m') > strtotime($optionDate)){
                    $optionDate = '';
                }
            }
        }

        if(empty($optionDate)){
            $year = date('Y');
            $month = date('m');
        }else{
            $year = date('Y',strtotime($optionDate));
            $month = date('m',strtotime($optionDate));
        }

        if($month < 7){
            $startMon = $year.'-01-01';
            $endMon = $year.'-06-30';
        }else{
            $startMon = $year.'-07-01';
            $endMon = $year.'-12-31';
        }

        $result = $traceLogMonth = TraceLogMonth::find()
            ->select('ApplicationId,Number,Month as dateline')
            ->where(['between', 'Month', date('Ym',strtotime($startMon)), date('Ym',strtotime($endMon))])
            ->asArray()->all();

        if(empty($result) && !empty($optionDate)){
            $result = TraceLog::find()
                ->select("count(*) as Number , ApplicationId , DATE(AddDate) as dateline")
                ->where(['between', 'AddDate', $startMon, $endMon])
                ->groupBy("DATE(AddDate) ,ApplicationId")
                ->asArray()->all();
        }

        $data = [];
        foreach($result as $k => $v){
            $formatDateline = substr($v['dateline'],0,7);
            if(!array_key_exists($formatDateline.'-01',$data)){
                $data[$formatDateline.'-01'] = $category;
            }
            if(isset($data[$formatDateline.'-01'][$v['ApplicationId']])){
                $v['ApplicationId'] = trim($v['ApplicationId']);
                $data[$formatDateline.'-01'][$v['ApplicationId']] += floatval($v['Number']);
            }
        }

        if (empty($traceLogMonth) && !empty($data) ) {
            $traceLogMonth = [];
            $Updatetime = date('Y-m-d h:i:s');
            $i = 0;
            foreach ($data as $key => $value) {
                foreach($value as $k => $v){
                    $traceLogMonth[$i][] = $k;
                    $traceLogMonth[$i][] =  $v;
                    $traceLogMonth[$i][] = date('Ym',strtotime($key));
                    $traceLogMonth[$i][] = $Updatetime;
                    $i++;
                }
            }

            #存入月统计表
            \Yii::$app->db->createCommand()
                ->batchInsert('TraceLog_month', ['ApplicationId','Number' , 'Month','Updatetime'],$traceLogMonth)
                ->execute();
        }

        $trace_series = [];

        if(!empty($data)){
            $category = array_values($data);
            $category = array_keys($category[0]);
        }

        $i = 0;
        foreach($data as $k => $v){
            arsort($data[$k]);
            $trace_series[$i]['name'] = $k;
            $trace_series[$i]['data'] = array_values($data[$k]);
            $i++;
        }

        return [
            'type' => 'month',
            'category' => $category,
            'trace_series' => $trace_series,
            'options' => self::$options
        ];
    }

    /**
     * 错误类型
     * @param bool $isReturnData
     * @return array
     */
    public static function getTraceCategory($isReturnData = true)
    {
        $ApplicationIds = TraceLog::find()
            ->select('ApplicationId,count(*) as Number')
            ->groupBy('ApplicationId')
            ->orderBy("count(*) DESC")
            ->asArray()->all();
        $total['name'] = '跟踪日志列表';
        foreach($ApplicationIds as $k => $v){
            $category[] = trim($v['ApplicationId']);
            $total['data'][] = floatval($v['Number']);
        }
        if(!$isReturnData){
            return $category;
        }
        return [
            'category' => $category,
            'series' => [$total],
        ];
    }

    /**
     * 月统计日期选项
     * @param string $year
     * @param string $mom
     * @param array $options
     */
    public static function options($year = '', $mom = '', $options = [])
    {
        if (empty($year) || empty($mom)) {
            $year = date('Y');
            $mom = date('m');
        }
        if ($year >= 2015) {
            if ($mom < 7) {
                $options[$year.'-06-'.'01'] = $year . '上半年';
                $year = $year - 1;
                self::options($year, 8, $options);
            } else {
                $options[$year.'-08-'.'01'] = $year . '下半年';
                self::options($year, 6, $options);
            }
        } else {
            self::$options = $options;
        }
    }
}

?>
