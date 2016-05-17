<?php

namespace console\services;

use common\models\Monitor;
use common\models\MonitorLog;
use backend\services\ZabbixCurlService;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class AutoZabbixService {

    /**
     * 处理昨天的数据
     */
    public static function fitZabbixYestoday() {
        //如果说程序突然OVER了，所以就找昨天没有数据的
        //配置昨天的参数信息
        $date = date("Y-m-d", strtotime("-1 day"));
        $starttime = strtotime(date("Y-m-d", strtotime("-2 day")));
        $endtime = strtotime($date);
        $fitIdLists = MonitorLog::find()->asArray()->distinct("id")->where("log_date!=:lastDate", [':lastDate' => $date])->all();

        if (empty($fitIdLists)) {
            //为空时处理所有的ID
            $fitArrayLists = Monitor::find()->orderBy("id asc")->limit(500)->all();
        } else {
            //只处理还没有处理的ID，当前语句未处理完整，后续测试再处理
            $fitArrayLists = Monitor::find()->orderBy("id asc")->limit(500)->all();
        }
        //获得需要处理的数据,默认只处理（昨天还没有处理的）500个数据，超过500个的话，那么就需要做优化了
        if (empty($fitArrayLists)) {
            echo '没有可以处理的数据';
            \Yii::$app->end();
        }
        foreach ($fitArrayLists as $oneDate) {
            //直接处理
            //通过接口获得数据
            $postData = [
                'jsonrpc' => '2.0',
                'method' => 'history.get',
                'params' => [
                    'history' => 0,
                    'itemids' => $oneDate->monitor_item,
                    'time_from' => $starttime,
                    'time_till' => $endtime,
                    'output' => 'extend'
                ],
                'id' => 0,
                'auth' => ''
            ];
            $reposeData = ZabbixCurlService::curlPostResult($postData, FALSE);
            print_r($reposeData);exit;
            //没有找到对应数据时处理异常
            if ($reposeData['status'] === false || empty($reposeData['info']->result)) {
                //没有信息就继续处理下一个
                continue;
            }
            //对得到的数据进行平均值处理
            var_dump($reposeData['info']);exit;
        }
    }

}

?>