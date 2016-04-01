<?php

namespace backend\services;

use Yii;
use common\models\Monitor;
use backend\services\ZabbixCurlService;

/**
 * Description of ToolService
 *
 * @author Administrator
 */
class ZabbixHightchartService {

    /**
     * 
     * @param type $condition
     * @param type $conPrams
     * @param type $limit
     * @param type $order
     * @return type
     */
    public static function find5Column() {
        //获得五个监控项的信息
        $monitorItems = Monitor::find()->limit(5)->orderBy("id desc")->all();
        if (empty($monitorItems)) {
            return [];
        }
        $chart5object = [];
        //循环处理得到的信息数据
        foreach ($monitorItems as $akey => $oneItem) {
            //配置数据并向ZABBIX获得数据
            //配置请求参数
            $endtime = time();
            //获得近三个钟头的信息
            $starttime = strtotime("-1 hour", $endtime);
            $postData = [
                'jsonrpc' => '2.0',
                'method' => 'history.get',
                'params' => [
                    'history' => 0,
                    'itemids' => $oneItem->monitor_item,
                    'time_from' => $starttime,
                    'time_till' => $endtime,
                    'output' => 'extend'
                ],
                'id' => 0,
                'auth' => ''
            ];
            $reposeData = ZabbixCurlService::curlPostResult($postData, FALSE);
            //没有找到对应数据时处理异常
            if ($reposeData['status'] === false) {
                $chart5object[$akey] = $reposeData;
                $chart5object[$akey]['texttitle'] = $oneItem->monitor_name;
                $chart5object[$akey]['server'] = $oneItem->monitor_host;
                continue;
            }
            $otherCountry = [];
            $otherCountry['texttitle'] = $oneItem->monitor_name;
            $otherCountry['server'] = $oneItem->monitor_host;
            foreach ($reposeData['info']->result as $oneDate) {
                $otherCountry['categories'][] = date('H:i:s', $oneDate->clock);
                $ceilnum=$oneDate->value*1000;
                if($ceilnum>0){
                    $ceilnum+=10;
                }
                $ceilnum=  ceil($ceilnum);
                $ceilnum=$ceilnum/1000;
                $otherCountry['series']['data'][] = floatval(round($ceilnum,2));
                $otherCountry['series']['name'] = '数量';
                $otherCountry['series']['color'] = 'red';
            }
            $chart5object[$akey] = $otherCountry;
        }
        return $chart5object;
    }

}

?>