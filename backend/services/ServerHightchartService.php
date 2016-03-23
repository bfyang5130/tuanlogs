<?php

namespace backend\services;

use yii\helpers\Url;
use backend\services\ServerStatusService;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class ServerHightchartService {

    /**
     * 
     */
    public static function findOneServerColumn($server, $date) {
        $dateString = ServerStatusService::findOneService24hStatus($server, $date);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        //记录时间函数
        $vist24Hours = [];
        //把10分钟的数据处理成24小时数据
        foreach ($dateString as $oneDate) {
            $fittime = date('H', strtotime($oneDate['log_time']));
            if (!empty($vist24Hours[$fittime]['memory_use'])) {
                $vist24Hours[$fittime]['memory_use']['data']+=floatval($oneDate['memory_use']);
                $vist24Hours[$fittime]['memory_use']['nums']++;
            } else {
                //未有的数据就放进去
                $otherCountry['categories'][] = $fittime;
                $vist24Hours[$fittime]['memory_use']['data'] = floatval($oneDate['memory_use']);
                $vist24Hours[$fittime]['memory_use']['nums'] = 1;
            }
            if (!empty($vist24Hours[$fittime]['memory_free'])) {
                $vist24Hours[$fittime]['memory_free']['data']+=floatval($oneDate['memory_free']);
                $vist24Hours[$fittime]['memory_free']['nums']++;
            } else {
                $vist24Hours[$fittime]['memory_free']['data'] = floatval($oneDate['memory_free']);

                $vist24Hours[$fittime]['memory_free']['nums'] = 1;
            }
            if (!empty($vist24Hours[$fittime]['cup_percent'])) {
                $vist24Hours[$fittime]['cup_percent']['data']+=floatval($oneDate['cup_percent']);
                $vist24Hours[$fittime]['memory_free']['nums']++;
            } else {
                $vist24Hours[$fittime]['cup_percent']['data'] = floatval($oneDate['cup_percent']);

                $vist24Hours[$fittime]['cup_percent']['nums'] = 1;
            }
        }
        //处理memory_use,memory_free数据
        $memory_useArray = [];
        $memory_freeArray = [];
        $cpu_Array = [];
        foreach ($vist24Hours as $key => $oneDate) {
            $memory_useArray[] = round(floatval($vist24Hours[$key]['memory_use']['data'] / $vist24Hours[$key]['memory_use']['nums'] / 1024 / 1024), 2);
            $memory_freeArray[] = round(floatval($vist24Hours[$key]['memory_free']['data'] / $vist24Hours[$key]['memory_free']['nums'] / 1024 / 1024), 2);
            $cpu_Array[] = round(floatval($vist24Hours[$key]['cup_percent']['data'] / $vist24Hours[$key]['cup_percent']['nums']), 2);
        }
        $otherCountry['memory']['series'] = [
            [
                'name' => '空闲内存',
                'color' => '#6adb6d',
                'data' => $memory_freeArray
            ],
            [
                'name' => '已用内存',
                'color' => '#fc4123',
                'data' => $memory_useArray
            ]
        ];
        $otherCountry['cpu']['series'] = [
            [
                'name' => 'CPU负载(%)',
                'color' => '#fc4123',
                'data' => $cpu_Array
            ]
        ];
        return ['in_country' => $otherCountry];
    }

}

?>