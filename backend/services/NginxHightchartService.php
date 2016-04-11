<?php

namespace backend\services;

use yii\helpers\Url;
use common\models\AccessLogSqlInject;
use common\models\AccessLogSqlInjectDay;
use common\models\AccessLogErrorStatusDay;
use yii\db\Query;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class NginxHightchartService {

    const AccessStatistic = 1;
    const AccessStatisticOne = 2;

    /**
     * 获得一个简单的饼状图
     * @return type
     */
    public static function getPieHightChart($date, $topType, $params, $groupstring, $tableselect, $moreClick = FALSE) {

        $dateString = NginxService::findGroupString($date, $topType, $params, $groupstring, $tableselect);
        if (empty($dateString)) {
            return [];
        }
//得到国内数据
        $fitOurCountry = \Yii::$app->params['domestic'];
        $ourCountry = [];
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            $url = Url::to('/nginx/city.html?table=' . $tableselect . '&date=' . $date . '&cityname=' . urlencode($oneDate[$groupstring]));
            if (in_array($oneDate[$groupstring], $fitOurCountry)) {
                $ourCountry['categories'][] = $oneDate[$groupstring];
                //再多一层的点击url
                if ($moreClick === true) {
                    $ourCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum']), 'url' => $url];
                } else {
                    $ourCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum'])];
                }

                $ourCountry['series']['colorByPoint'] = TRUE;
                $ourCountry['series']['name'] = '访问量';
            } else {
                $otherCountry['categories'][] = $oneDate[$groupstring];
                if ($moreClick === true) {
                    $otherCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum']), 'url' => $url];
                } else {
                    $otherCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum'])];
                }
                $otherCountry['series']['colorByPoint'] = TRUE;
                $otherCountry['series']['name'] = '访问量';
            }
        }
        return ['in_country' => $ourCountry, 'out_country' => $otherCountry];
    }

    /**
     * 获得一个简单的饼状图
     * @return type
     */
    public static function getPiePlatHightChart($date, $topType, $params, $groupstring, $tableselect, $moreClick = FALSE) {

        $dateString = NginxService::findGroupString($date, $topType, $params, $groupstring, $tableselect);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            if (trim($oneDate[$groupstring]) == '') {
                $oneDate[$groupstring] = '其他';
            }
            $otherCountry['categories'][] = $oneDate[$groupstring];
            $otherCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum'])];
            $otherCountry['series']['colorByPoint'] = TRUE;
            $otherCountry['series']['name'] = '访问量';
        }
        return ['in_country' => $otherCountry];
    }

    /**
     * 获得一个简单的线条图
     * @return type
     */
    public static function getSplinePlatHightChart($date, $topType, $params, $groupstring, $tableselect, $moreClick = FALSE) {

        $dateString = NginxService::findGroupString($date, $topType, $params, $groupstring, $tableselect);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        $otherCountry['series']['name'] = array_values($params);
        $vist24Hours = [];
        //把10分钟的数据处理成24小时数据
        foreach ($dateString as $oneDate) {
            $fittime = date('H', strtotime($oneDate[$groupstring]));
            if (!empty($vist24Hours[$fittime])) {
                $vist24Hours[$fittime]+=floatval($oneDate['totalNum']);
            } else {
                //未有的数据就放进去
                $otherCountry['categories'][] = date('H', strtotime($oneDate[$groupstring]));
                $vist24Hours[$fittime] = floatval($oneDate['totalNum']);
            }
        }
        foreach ($vist24Hours as $key => $oneDate) {
            $otherCountry['series']['data'][] = [$key, $oneDate];
        }
        return ['in_country' => $otherCountry];
    }

    /**
     * 获得首页要显示的访问情况
     */
    public static function pageAttack() {
        //获得总的攻击信息记录数
        $sqlattack = AccessLogSqlInject::find()->count();
        //获得访问出错的数据
        $query = new Query;
        $dateString = $query->select("count(*) nums,error_status")->from('AccessLogErrorStatus')->groupBy("error_status")->orderBy("nums desc")->all();
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        $otherCountry['categories'][] = '注入';
        $otherCountry['series']['data'][] = floatval($sqlattack);
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['error_status'];
            $otherCountry['series']['data'][] = floatval($oneDate['nums']);
            $otherCountry['series']['name'] = '数量';
            $otherCountry['series']['color'] = 'red';
        }

        return ['in_country' => $otherCountry];
    }

    /**
     * 获得最近五天的错误状态进行展示
     * @return type
     */
    public static function findAllLine() {
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = AccessLogErrorStatusDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All();
        //如果为空，就取最后一个时间，以最后一个时间点为准
        if (empty($fiveDateLists)) {
            $lastDay = AccessLogErrorStatusDay::find()->orderBy("Id desc")->one();
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->StatisticDate));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = AccessLogErrorStatusDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All();

            if (empty($fiveDateLists)) {
                return [];
            }
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['StatisticDate']));
            $fitdates[$oneDate['error_status']][$toIntDay] = floatval($oneDate['Amount']);
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