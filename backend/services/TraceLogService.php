<?php

namespace backend\services;

use common\models\TraceLog;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\models\TraceLogDay;
use yii\helpers\ArrayHelper;
use yii\db\Query;

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

    public static function CountDay()
    {
        //按照天数算的总记录
        $totalCount = TraceLog::find()
            ->select("(TO_DAYS(now()) - TO_DAYS(`AddDate`)) as `total`")
            ->orderBy('`AddDate`')->limit(1)->asArray()->one();
        #分页
        $pager = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $totalCount['total'],
        ]);

        #所有错误类别
        $category = TraceLog::find()->select('ApplicationId')->groupBy('ApplicationId')->asArray()->all();
        $category = array_values(ArrayHelper::map($category,'ApplicationId','ApplicationId'));

        $coefficient = 5 * \Yii::$app->request->get('page',1);

        for($i=0;$i < 5; $i++){
            $day = $i - $coefficient + 1  ;
            $selectDay = date("Y-m-d",strtotime("$day day"));
            $highcharts_title[] = $selectDay;
            $TraceLogList[$selectDay] = TraceLogDay::find()
                ->select("ApplicationId,Number,`Date`")
                ->where(["FROM_UNIXTIME(`Date`,'%Y-%m-%d')"=>$selectDay])
                ->asArray()->all();
            if($selectDay == date("Y-m-d")){
                $TraceLogList[$selectDay] = TraceLog::find()
                    ->select("count(*) as Number , ApplicationId ")
                    ->where(['date(`AddDate`)'=>$selectDay])
                    ->groupBy("ApplicationId")
                    ->asArray()->all();
                continue;
            }
            if(empty($TraceLogList[$selectDay])){
                $TraceLogList[$selectDay] = TraceLog::find()
                    ->select("count(*) as Number , ApplicationId ")
                    ->where(['date(`AddDate`)'=>$selectDay])
                    ->groupBy("ApplicationId")
                    ->asArray()->all();
                if (empty($TraceLogList[$selectDay])) {
                    #存入日统计数据
                    $traceLogDayModel = new TraceLogDay();
                    $traceLogDayModel->ApplicationId = '';
                    $traceLogDayModel->Number = 0;
                    $traceLogDayModel->Updatetime = time();
                    $traceLogDayModel->Date = strtotime($selectDay);
                    $traceLogDayModel->insert();
                }else{
                    #存入日统计数据
                    foreach($TraceLogList[$selectDay] as $key => $value){
                        $traceLogDayModel = new TraceLogDay();
                        $traceLogDayModel->ApplicationId = $value['ApplicationId'];
                        $traceLogDayModel->Number = $value['Number'];
                        $traceLogDayModel->Updatetime = time();
                        $traceLogDayModel->Date = strtotime($selectDay);
                        $traceLogDayModel->insert();
                    }
                }
            }
        }

        $data = $TraceLogList;

        $trace_series = [];
        $i = 0;

        foreach($data as $key => $value){
            $trace_series[$i]['name'] = $key;
            $trace_series[$i]['data'] = [];
            $trace_series[$i]['data'] = array_pad( $trace_series[$i]['data'], count($category),0);
            foreach($value as $k => $v){
                $categoryExistsKey = array_search($v['ApplicationId'],$category);
                $trace_series[$i]['data'][$categoryExistsKey] = intval($v['Number']);
            }
            $i++;
        }

        return [
            'type' => 'day',
            'pagination' => $pager,
            'category' => $category,
            'trace_series' => $trace_series,
            'highcharts_title' => implode(' ',$highcharts_title)
        ];
    }
}

?>
