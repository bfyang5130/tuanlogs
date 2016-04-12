<?php

namespace backend\services;

use common\models\SqlTraceLongSqlDay;
use yii\db\Query;
use yii\helpers\Url;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class SqlHightchartService {

    /**
     * 获得五个错误的柱状图
     * @param type $condition
     * @param type $conPrams
     * @param type $limit
     * @param type $order
     * @return type
     */
    public static function find5Column($condition, $conPrams, $groupstring) {
        $query = new Query;
        $dateString = $query->select("*,sum(Amount) nums")->from('SqlTrace_LongSqlDay')->where($condition, $conPrams)->groupBy($groupstring)->orderBy("nums desc")->all();
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['databasetype'];

            $otherCountry['series']['data'][] = ['url' => Url::toRoute('/sql/longtimesql') . '?LongtimesqlSearch%5Bdatabasetype%5D=' . $oneDate['databasetype'], 'name' => $oneDate['databasetype'], 'y' => floatval($oneDate['nums'])];
            $otherCountry['series']['name'] = '数量';
            $otherCountry['series']['color'] = 'red';
        }
        return ['in_country' => $otherCountry];
    }

    public static function findAllLine() {
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = SqlTraceLongSqlDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All();
        //如果为空，就取最后一个时间，以最后一个时间点为准
        //标记是否为最新的数据
        $isToday = true;
        if (empty($fiveDateLists)) {
            $lastDay = SqlTraceLongSqlDay::find()->orderBy("Id desc")->one();
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->StatisticDate));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = SqlTraceLongSqlDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All();

            if (empty($fiveDateLists)) {
                return [];
            }
            $isToday = false;
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['StatisticDate']));
            $fitdates[$oneDate['databasetype']][$toIntDay] = floatval($oneDate['Amount']);
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
        //标记是否为今天的数据，如果是今天的数据,那么对今天的数据进行实时统计处理
        if ($isToday) {
            $fitDate = date('Y-m-d 00:00:00');

            $after1Date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($fitDate)));
            //因为上面已经做了判断，所以今天的数据肯定会存在
            //获得今天最新的数据
            $errorstatusLists = \common\models\SqlTraceLong::find()->select("count(*) nums,databasetype")->where('executedate>:sd AND executedate<=:ed', [':sd' => $fitDate, ':ed' => $after1Date])->groupBy('databasetype')->indexBy('databasetype')->asArray()->all();

            //对上面的数组进行处理
            foreach ($outCharts['series'] as $key => $oneVaue) {
                //把最近一个元素推出
                array_pop($outCharts['series'][$key]['data']);
                //推入一个新元素
                //如果没有这个数组就推入一个0的数据
                if (!isset($errorstatusLists[$oneVaue['name']]['nums'])) {
                    array_push($outCharts['series'][$key]['data'], 0);
                } else {
                    array_push($outCharts['series'][$key]['data'], floatval($errorstatusLists[$oneVaue['name']]['nums']));
                }
            }
        }
        return $outCharts;
    }

}

?>