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
        $monitorItems = Monitor::find()->where('is_index=1')->limit(5)->orderBy("id desc")->all();
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
            $starttime = strtotime("-10 minute", $endtime);
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
            if (empty($reposeData['info']->result)) {
                $chart5object[$akey] = $reposeData;
                $chart5object[$akey]['texttitle'] = $oneItem->monitor_name;
                $chart5object[$akey]['server'] = $oneItem->monitor_host;
                continue;
            }
            foreach ($reposeData['info']->result as $oneDate) {
                $otherCountry['categories'][] = date('H:i:s', $oneDate->clock);
                $ceilnum = $oneDate->value * 1000;
                if ($ceilnum > 0) {
                    $ceilnum+=10;
                }
                $ceilnum = ceil($ceilnum);
                $ceilnum = $ceilnum / 1000;
                $otherCountry['series']['data'][] = floatval(round($ceilnum, 2));
                $otherCountry['series']['name'] = '数量';
                $otherCountry['series']['color'] = 'red';
            }
            $chart5object[$akey] = $otherCountry;
        }
        return $chart5object;
    }

    /**
     * 获得对应选择的一个监控数据进行查询
     * @return string
     */
    public static function findSelectColumnFit($id, $stime, $etime) {
        $oneItem = Monitor::find()->where("id=:id", [':id' => $id])->one();
        if (!$oneItem) {
            return [];
        }
        //配置数据并向ZABBIX获得数据
        //配置请求参数
        $endtime = strtotime($etime);
        //获得近三个钟头的信息
        $starttime = strtotime($stime);
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
            return [];
        }

        $otherCountry = [];
        $otherCountry['title']['text'] = $oneItem->monitor_name;
        $otherCountry['server'] = $oneItem->monitor_host;
        if (empty($reposeData['info']->result)) {
            return [];
        }
        foreach ($reposeData['info']->result as $oneDate) {
            $otherCountry['xAxis']['data'][] = date('H:i:s', $oneDate->clock);
            $ceilnum = $oneDate->value * 1000;
            if ($ceilnum > 0) {
                $ceilnum+=10;
            }
            $ceilnum = ceil($ceilnum);
            $ceilnum = $ceilnum / 1000;
            $otherCountry['series']['data'][] = floatval(round($ceilnum, 2));
            $otherCountry['series']['name'] = '使用量';
        }
        //获得总共多少个数据
        $nums = count($reposeData['info']->result);
        //处理显示的比例
        $showlimit = round(6000 / $nums, 2);
        $otherCountry['showlimit'] = 100 - $showlimit;
        return $otherCountry;
    }

    /**
     * 获得对应选择的一个监控数据进行查询
     * @return string
     */
    public static function findSelectColumn($id, $stime, $etime) {
        $oneItem = Monitor::find()->where("id=:id", [':id' => $id])->one();
        if (!$oneItem) {
            return [[], 0];
        }
        //配置数据并向ZABBIX获得数据
        //配置请求参数
        $endtime = strtotime($etime);
        //获得近三个钟头的信息
        $starttime = strtotime($stime);
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
            return [[], 0];
        }

        $otherCountry = [];
        $otherCountry['texttitle'] = $oneItem->monitor_name;
        $otherCountry['server'] = $oneItem->monitor_host;
        if (empty($reposeData['info']->result)) {
            return [[], 0];
        }
        foreach ($reposeData['info']->result as $oneDate) {
            $otherCountry['categories'][] = date('H:i:s', $oneDate->clock);
            $ceilnum = $oneDate->value * 1000;
            if ($ceilnum > 0) {
                $ceilnum+=10;
            }
            $ceilnum = ceil($ceilnum);
            $ceilnum = $ceilnum / 1000;
            $otherCountry['series']['data'][] = floatval(round($ceilnum, 2));
            $otherCountry['series']['name'] = '数量';
            $otherCountry['series']['color'] = 'red';
        }
        return [$otherCountry, $oneItem->is_index];
    }

    public static function getSelectId() {
        $postSelect = \Yii::$app->request->post();
        if (!isset($postSelect['MonitorSelectForm'])) {
            $id = 1;
            $stime = Date('Y-m-d 00:00:00');
            $etime = Date('Y-m-d 01:00:00');
        } else {
            $id = $postSelect['MonitorSelectForm']['selectid'];
            $stime = $postSelect['MonitorSelectForm']['stime'];
            $etime = $postSelect['MonitorSelectForm']['etime'];
            if (strtotime($stime) == strtotime($etime)) {
                $etime = date("Y-m-d H:is", strtotime("+1 hour", strtotime($stime)));
            }
        }
        return [$id, $stime, $etime];
    }

    /**
     * 处理两天的对比数据
     */
    public static function fitTwoDay() {
        //获得ID，获得时间
        $monitor_id = \Yii::$app->request->get('monitor_id');
        $date = \Yii::$app->request->get('date');
        //如果这两个都为空那么就直接出错吧
        if (empty($monitor_id) || empty($date)) {
            return [];
        }

        //获得昨天到现在的数据最多共96条数据
        $startdate = date("Y-m-d 00:00:00", strtotime("-1 day", strtotime($date)));

        $enddate = date("Y-m-d 00:00:00", strtotime("+1 day", strtotime($date)));
        //获得区间数据
        $selectidLists = \common\models\MonitorLog::find()->where(['between', 'log_date', $startdate, $enddate])->orderBy("log_date desc")->all();
        if (empty($selectidLists)) {
            return [];
        }
        //处理这些数据
        //定义三个标记数据
        $startdatestr = strtotime($startdate);
        $middatestr = strtotime(date("Y-m-d 00:00:00", strtotime($date)));
        //定义legend.data
        $legend['data'] = [date('Y-m-d', $startdatestr), date('Y-m-d', $middatestr)];
        //定义两个数组
        $arrone = [];
        $arrtwo = [];
        //定义一个xaxis.data
        $xaxis['data'] = [];
        for ($i = 0; $i < 48; $i++) {
            $key1 = date('Y-m-d H:i:s', $startdatestr);
            $key2 = date('Y-m-d H:i:s', $middatestr);
            $arrone[$key1] = 0;
            $arrtwo[$key2] = 0;
            $startdatestr+=1800;
            $middatestr+=1800;
            $xaxis['data'][] = date('H:i', $startdatestr);
        }
        foreach ($selectidLists as $onSelectItem) {
            if (isset($arrone[$onSelectItem->log_date])) {
                $arrone[$onSelectItem->log_date] = $onSelectItem->serize_string;
            } else {
                $arrtwo[$onSelectItem->log_date] = $onSelectItem->serize_string;
            }
        }
        //已经处理好上面的两组数据现在要生成一个echarts能认得的数组数据
        //json legend.data
        //xAxis.data
        //series
        $series = [
            [
                'name' => $legend['data'][0],
                'type' => 'bar',
                'data' => array_values($arrone)
            ],
            [
                'name' => $legend['data'][1],
                'type' => 'bar',
                'data' => array_values($arrtwo)
            ]
        ];
        $returnArray = [
            'legenddata' => $legend['data'],
            'xAxisdata' => $xaxis['data'],
            'series' => $series,
        ];
        return $returnArray;
    }

}

?>