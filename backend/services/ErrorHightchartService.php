<?php

namespace backend\services;

use backend\services\AppcationNameService;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class ErrorHightchartService {

    /**
     * 获得五个错误的柱状图
     * @param type $condition
     * @param type $conPrams
     * @param type $limit
     * @param type $order
     * @return type
     */
    public static function find5Column($condition, $conPrams, $limit, $order) {
        $dateString = AppcationNameService::findAppliName($condition, $conPrams, $limit, $order);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['appname'];
            $otherCountry['series']['data'][] = floatval($oneDate['logtotal']);
            $otherCountry['series']['name'] = '数量';
            $otherCountry['series']['color'] = 'red';
        }
        return ['in_country' => $otherCountry];
    }

    public static function findAllLine() {
        #获得所有错误类型
        $allErrorsType = \common\models\ApplicateName::find()->asArray()->select('appname')->where('logtype=0')->all();
        foreach ($allErrorsType as $oneDate) {
            $fitAllErrorsType[] = $oneDate['appname'];
        }
        $whereString = implode(',', $fitAllErrorsType);
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = \common\models\ErrorLogDay::find()->where(["ApplicationId" => $fitAllErrorsType])->andWhere('Date>:sd AND Date<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("Date desc")->All();
        //如果为空，就取最后一个时间，以最后一个时间点为准
        if (empty($fiveDateLists)) {
            $lastDay = \common\models\ErrorLogDay::find()->orderBy("Id desc")->one();
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->Date));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = \common\models\ErrorLogDay::find()->where(["ApplicationId" => $fitAllErrorsType])->andWhere('Date>:sd AND Date<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("Date desc")->All();

            if (empty($fiveDateLists)) {
                return [];
            }
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['Date']));
            $fitdates[$oneDate['ApplicationId']][$toIntDay] = floatval($oneDate['Number']);
        }
        #对处理了的数据再处理一遍，不存在的数据加上0
        $outCharts = [];
        $outCharts['categories'] = [];
        $outCharts['series'] = [];
        foreach ($fitdates as $key => $oneDate) {
            $start_date = date('Y-m-d', strtotime($fitDate));
            for ($i = 1; $i <= 5; $i++) {
                if (!in_array($start_date, $outCharts['categories'])) {
                    $outCharts['categories'][] = $start_date;
                }
                if (!isset($oneDate[$start_date])) {
                    $fitdates[$key][$start_date] = 0;
                }
                $start_date = date('Y-m-d', strtotime("-1 day", strtotime($start_date)));
            }
            ksort($fitdates[$key]);
        }
        foreach ($fitdates as $key => $oneDate) {
            $outCharts['series'][] = [
                'name' => $key,
                'data' => array_values($oneDate),
            ];
        }
        $outCharts['categories'] = array_reverse($outCharts['categories']);
        return $outCharts;
    }

}

?>