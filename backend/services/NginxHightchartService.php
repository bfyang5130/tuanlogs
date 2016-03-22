<?php

namespace backend\services;

use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;

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

}

?>