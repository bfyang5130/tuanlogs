<?php

namespace backend\services;

use common\models\AccessStatistic;
use common\models\AccessStatisticOne;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class NginxService {

    const AccessStatistic = 1;
    const AccessStatisticOne = 2;

    /**
     * 获得某一天的访问人数
     */
    public static function findOneVisitis($data, $tableselect) {

        if (empty($data)) {
            $date = date("Y-m-d 00:00:00");
        }
        $start_date = date('Y-m-d 00:00:00', strtotime($data));
        $end_date = date('Y-m-d 00:00:00', strtotime('+1 d', strtotime($start_date)));
        $query = null;
        switch ($tableselect) {
            case self::AccessStatistic:
                $query = AccessStatistic::find();
                break;
            case self::AccessStatisticOne:
                $query = AccessStatisticOne::find();
            default :
                $query = AccessStatistic::find();
        }
        return $query->where("TopType='status' AND CheckTime>=:sT AND CheckTime<=:eT", [':sT' => $start_date, ':eT' => $end_date])->sum('Amount');
    }

}

?>