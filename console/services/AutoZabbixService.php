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
        //数据太多了，只能取一个钟头的数据，要不会中弹，安全保证标准是一分钟处理6个数据，一个钟头就处理360个作为上限
        //配置昨天的参数信息
        $date = date("Y-m-d H:00:00", strtotime("-1 hour"));
        //时间标记
        $fitdate=date("Y-m-d H:30:00", strtotime("-1 hour"));
        $timemark = strtotime($fitdate);
        $starttime = strtotime($date);
        $endtime = strtotime(date("Y-m-d H:00:00"));
        $fitIdLists = MonitorLog::find()->asArray()->distinct("monitor_id")->where("log_date=:lastDate", [':lastDate' => $date])->all();
        if (empty($fitIdLists)) {
            //为空时处理所有的ID
            $fitArrayLists = Monitor::find()->orderBy("id asc")->limit(360)->all();
        } else {
            $donotfitstring=array();
            foreach ($fitIdLists as $oneFit) {
                $donotfitstring[]=$oneFit['monitor_id'];
            }
            //只处理还没有处理的ID，当前语句未处理完整，后续测试再处理
            $fitArrayLists = Monitor::find()->where(['not in', 'id', $donotfitstring])->orderBy("id asc")->limit(360)->all();
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

            //没有找到对应数据时处理异常
            if ($reposeData['status'] === false || empty($reposeData['info']->result)) {
                //没有信息就继续处理下一个
                continue;
            }
            //对得到的数据进行平均值处理
            $logtime = [
                0 => ['value' => 0, 'count' => 0],
                1 => ['value' => 0, 'count' => 0]
            ];
            foreach ($reposeData['info']->result as $oneResDate) {
                if ($oneResDate->clock <= $timemark) {
                    $logtime[0]['value']+=$oneResDate->value;
                    $logtime[0]['count']+=1;
                } else {
                    $logtime[1]['value']+=$oneResDate->value;
                    $logtime[1]['count']+=1;
                }
            }
            $monitorlogs = [
                [$oneDate->id,  round($logtime[0]['value']/$logtime[0]['count'],2),$date],
                [$oneDate->id,  round($logtime[0]['value']/$logtime[0]['count'],2),$fitdate]
            ];
            //处理平均值并入库
            try {
                $command = \Yii::$app->db->createCommand();
                $command->batchInsert(
                        MonitorLog::tableName(), [
                    'monitor_id', 'serize_string', 'log_date'
                        ], $monitorlogs);
                $command->execute();
            } catch (yii\db\Exception $e) {
                //记录读到的最后一行,没有处理的行会被后退
                print_r($e);
                continue;
            }
        }
    }

}

?>