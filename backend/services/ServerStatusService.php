<?php

namespace backend\services;

use common\models\MemoryStatus;
use yii\db\Query;

/**
 * Description of ServerStatusService
 * 处理服务器实时状态的信息
 * @author Administrator
 */
class ServerStatusService {

    /**
     * 获得一个服务器24小时的服务状态信息
     * @return string
     */
    public static function findOneService24hStatus($server = '', $date = '') {
        if (empty($date)) {
            $date = date("Y-m-d 00:00:00");
        }
        $start_date = date('Y-m-d 00:00:00', strtotime($date));
        $end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($start_date)));
        $query = MemoryStatus::find();
        return $query->where("system_comefrom=:syscome AND log_time>=:sT AND log_time<=:eT", [':syscome' => $server, ':sT' => $start_date, ':eT' => $end_date])->all();
    }

}

?>