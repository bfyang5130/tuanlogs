<?php

namespace backend\services;

use common\models\ApplicateName;
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

    public function search()
    {
        $params = Yii::$app->request->get();

        $query = TraceLog::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * 日统计
     * @return array
     * @throws \Exception
     */
    public static function CountDay()
    {

        $search_date  = \Yii::$app->request->get('search_date',date('Y-m-d',time()));

        #所有错误类别
        $category = [];
        foreach(self::getTraceCategory(false) as $k =>$v){
            $category[$v] = floatval(0);
        }

        $page = \Yii::$app->request->get('page',1);

        if(time() - strtotime($search_date)   >  86400 * 2  ){
            $start = date('Y-m-d' ,strtotime($search_date) + 3*86400) ;
            $end = date('Y-m-d',strtotime($start) - 86400 * 5 );
        }else{
            $start = date('Y-m-d' ,time() - 86400 * 5 * ($page - 1) + 86400) ;
            $end = date('Y-m-d',strtotime($start) - 86400 * 5 );
            $search_date = '';
        }

        $isUpdate = false;//是否更新统计表

        if($page > 1 || !empty($search_date)) {
            $TraceLogList = $trace_day_log = TraceLogDay::find()
                ->select("ApplicationId , date(`Date`) as dateline ,Number  ")
                ->where(['between','Date',$end,date('Y-m-d',strtotime($start) - 86400)])
                ->asArray()->all();

            if ($page > 1 && empty($TraceLogList) ) {
                $isUpdate = true;
            }

            if(empty($TraceLogList) &&  !empty($search_date)){
                $isUpdate = true;
            }

            if (!empty($TraceLogList)) {
                #查询某天没有数据
                $selectDay = $end;
                $noDataDay = [];

                for($i = 0;$i < 5;$i++){
                    $noDataDay[date('Y-m-d', strtotime($selectDay)  + 86400 * $i)] = 0;
                }

                foreach ($trace_day_log as $k => $v) {
                    if (array_key_exists($v['dateline'], $noDataDay)) {
                        $noDataDay[$v['dateline']] = 1;
                    }
                }

                if (in_array(0, $noDataDay)) {
                    $in = [];
                    foreach ($noDataDay as $k => $v) {
                        if ($v == 0) {
                            $in[] = $k;
                        }
                    }

                    $TraceLogList = $trace_day_log = '';
                    $isUpdate = true;
                }

            }
        }

        if(empty($TraceLogList) || isset($selectAll) ){
            $TraceLogList = TraceLog::find()
                ->select("count(1) as Number , ApplicationId ,date(`AddDate`) as `dateline`  ")
                ->where(['between','AddDate',$end,$start])
                ->groupBy("date(`AddDate`),ApplicationId")
                ->asArray()->all();
        }

        #错误类型排序

        foreach($TraceLogList as $k => $v){
            @$category[$v['ApplicationId']] += floatval($v['Number']);
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
            $result[date('Y-m-d',strtotime($start) - 86400 * $i)] = $category;
        }

        foreach($TraceLogList as $k => $v){
            $result[$v['dateline']][trim($v['ApplicationId'])] +=  floatval($v['Number']);
        }

        #数据存入日统计表
        if(empty($trace_day_log) && $isUpdate ){
            $i = 0;
            $x = 0;
            foreach($result as $k => $v){
                $date = date('Y-m-d',strtotime($k));
                if(  !empty($in) && in_array($k,$in)){
                    foreach ($v as $kk => $vv) {
                        $searchInsertData[$x][] = $kk;
                        $searchInsertData[$x][] = $vv;
                        $searchInsertData[$x][] = $date;
                        $searchInsertData[$x][] = date('Y-m-d h:i:s');
                        $x++;
                    }
                }
                foreach ($v as $kk => $vv) {
                    $insertData[$i][] = $kk;
                    $insertData[$i][] = $vv;
                    $insertData[$i][] = $date;
                    $insertData[$i][] = date('Y-m-d h:i:s');
                    $i++;
                }
            }

            if(!empty($searchInsertData)){
                $insertData = $searchInsertData;
            }

            \Yii::$app->db->createCommand()
                ->batchInsert('TraceLog_day', ['ApplicationId','Number' , 'Date','Updatetime'],$insertData)
                ->execute();
        }

        $data = [];
        $i = 0;
        foreach($result as $k => $v){
            $data[$i]['name'] = $k;
            $data[$i]['data'] = array_values($v);
            $i++;
        }

        return [
            'type' => 'day',
            'category' => $series_categroy,
            'trace_series' => $data,
            'highcharts_title' => '',
            'search_date' => $search_date

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
                ->select("count(1) as Number , ApplicationId , DATE(AddDate) as dateline")
                ->where(['between', 'AddDate', $startMon, $endMon])
                ->groupBy("DATE(AddDate) ,ApplicationId")
                ->asArray()->all();
        }

        $data = [];
        foreach($result as $k => $v){
            $formatDateline = substr($v['dateline'],0,7);
            if(!array_key_exists($formatDateline,$data)){
                $data[$formatDateline] = $category;
            }
            if(isset($data[$formatDateline][$v['ApplicationId']])){
                $v['ApplicationId'] = trim($v['ApplicationId']);
                $data[$formatDateline][$v['ApplicationId']] += floatval($v['Number']);
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
            'options' => self::$options,
        ];
    }

    /**
     * 错误类型
     * @param bool $isReturnData
     * @return array
     */
    public static function getTraceCategory($isReturnData = true)
    {
        if($isReturnData){
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
            return [
                'category' => $category,
                'series' => [$total],
            ];
        }else{
            $ApplicationIds = ApplicateName::find()
                ->select('appname as ApplicationId')
                ->where(['logtype'=>1])->asArray()->all();
            foreach($ApplicationIds as $k => $v){
                $category[] = trim($v['ApplicationId']);
            }
            if(!$isReturnData){
                return $category;
            }
        }
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
