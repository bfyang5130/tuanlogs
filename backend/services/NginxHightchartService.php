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
            $url = Url::to('/nginx/city.html?table='.$tableselect.'&date=' . $date . '&cityname=' . urlencode($oneDate[$groupstring]));
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
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = date('H:i:s',strtotime($oneDate[$groupstring]));
            $otherCountry['series']['data'][] = [date('H:i:s',strtotime($oneDate[$groupstring])), floatval($oneDate['totalNum'])];
            
        }
        return ['in_country' => $otherCountry];
    }
}

?>