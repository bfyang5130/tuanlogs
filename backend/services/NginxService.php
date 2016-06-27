<?php

namespace backend\services;

use common\models\AccessStatistic17;
use common\models\AccessStatistic21;
use yii\db\Query;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class NginxService {

    const AccessStatistic = 1;
    const AccessStatisticOne = 2;
    const AccessStatistic17 = 3;
    const AccessStatistic21 = 4;

    /**
     * 获得某一天的访问人数
     */
    public static function findOneTypeAmounts($date, $topType, $tableselect) {

        if (empty($date)) {
            $date = date("Y-m-d 00:00:00");
        }
        $start_date = date('Y-m-d 00:00:00', strtotime($date));
        $end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($start_date)));
        $query = null;
        switch ($tableselect) {
            case self::AccessStatistic17:
                $query = AccessStatistic17::find();
                break;
            case self::AccessStatistic21:
                $query = AccessStatistic21::find();
                break;
            default :
                $query = AccessStatistic17::find();
        }
        return $query->where("TopType=:topT AND CheckTime>=:sT AND CheckTime<=:eT", [':topT' => $topType, ':sT' => $start_date, ':eT' => $end_date])->sum('Amount',\Yii::$app->db1);
    }

    /**
     * 获得一个按组分类的数据 
     */
    public static function findGroupString($date, $topType, $params, $groupstring, $tableselect) {

        if (empty($date)) {
            $date = date("Y-m-d 00:00:00");
        }
        $start_date = date('Y-m-d 00:00:00', strtotime($date));
        $end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($start_date)));
        $query = new Query;
        $query->where("CheckTime>=:sT AND CheckTime<=:eT", [':sT' => $start_date, ':eT' => $end_date]);
        switch ($tableselect) {
            case self::AccessStatistic17:
                $query = $query->from('AccessStatistic17');
                break;
            case self::AccessStatistic21:
                $query = $query->from('AccessStatistic21');
                break;
            default :
                $query = $query->from('AccessStatistic17');
        }
        return $query->select("$groupstring,sum(Amount) totalNum")->andwhere($topType, $params)->groupBy($groupstring)->all(\Yii::$app->db1);
    }

}

?>