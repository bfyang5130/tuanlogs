<?php

namespace backend\services;

use common\models\ApplicateName;
use common\models\ErrorLog;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of SqlTraceService
 *
 * @author Administrator
 */
class SqlTraceService {

    /**
     * 获得数据库对应数据
     * @return type
     */
    public static function getSqlTraceDbType() {
        $query = new Query();
        $query->select("databasetype")
                ->from("SqlTrace")
                ->distinct();
        $dbtypes = $query->all();
        $dbtype_item = ArrayHelper::map($dbtypes, "databasetype", "databasetype");
        return $dbtype_item;
    }

    /**
     * 
     * @param type $page
     * @param type $search_date
     * @return type
     * 获得查询天数对应的需要的统计数据
     */
    public static function getSqlDayGraph($page, $search_date) {

        //设置不超时 首次运行会统计数据,比较慢
        set_time_limit(0);
        #判断是否有选择查询的日期，或者所查询的日期是否超过今天,如果超过今天，以今天为准
        if (empty($search_date)) {
            $search_date = date("Y-m-d");
        } else {
            if (strtotime($search_date) > time()) {
                $search_date = date("Y-m-d");
            }
        }
        $search_date = date("2016-1-4");
        #如果是今天的数据，进行实时查询
        if (strtotime($search_date) === strtotime(date("Y-m-d"))) {
            $search_date = date('Y-m-d', strtotime("$search_date -1 day"));
            #查询实时数据
            $application_list = \Yii::$app->db->createCommand('select databasetype,count(0) Number,date_format(executedate,"%Y-%m-%d %H:00:00") Date,now() Updatetime,sum(sqlusedtime) totoltime
                                              from SqlTrace where executedate between :exdate and :exdate1 group by databasetype, date_format(executedate,"%Y-%m-%d %H:00:00")
                                              ', [':exdate' => $search_date, ':exdate1' => date('Y-m-d', strtotime("$search_date +1 day"))])->queryAll();
        } else {
            #从已经有的数据里取出数据
            $application_query = new Query();
            $application_query->select("*")
                    ->from("SqlTrace_day")
                    ->where("`Date` between :exdate and :exdate1", [':exdate' => $search_date, ':exdate1' => date('Y-m-d', strtotime("$search_date +1 day"))]);
            $application_list = $application_query->all();
            if (empty($application_list)) {
                \Yii::$app->db->createCommand('insert into `SqlTrace_day`(`databasetype`,`Number`,`Date`,Updatetime,`totoltime`) select databasetype,count(0) Number,date_format(executedate,"%Y-%m-%d %H:00:00") Date,now() Updatetime,sum(sqlusedtime) totoltime
                                              from SqlTrace where executedate between :exdate and :exdate1 group by databasetype, date_format(executedate,"%Y-%m-%d %H:00:00")  having databasetype is not null order by null
                                              ', [':exdate' => $search_date, ':exdate1' => date('Y-m-d', strtotime("$search_date +1 day"))])->queryAll();

                $application_list = $application_query->all();
            }
        }
        #处理数据
        $dabasetype=[];
        $dabasetTotalValue=[];
        $i=0;
        foreach ($application_list as $oneDate) {
            #如果已经存在数组里不存在这个类型,把当前的类型添加进去
            if(!in_array($oneDate['databasetype'], $dabasetype)){
                array_push($dabasetype, $oneDate['databasetype']);
                $dabasetTotalValue[$i]=floatval($oneDate['Number']);
                $i++;
            }
        }
        return array("search_date"=>$search_date,"items" => $dabasetTotalValue, "appnames" => $dabasetype);
        exit;
        //查找Error的appname
        $application_query = new Query();
        $application_query->select("appname")
                ->from("ApplicateName")
                ->where("logtype=1"); //1-Error类型 0-trace
        $application_list = $application_query->all();
        $appname_list = array();
        foreach ($application_list as $value) {
            $appname_list[] = $value['appname'];
        }

        $errorlogday = ErrorLogDay::find()
                ->orderBy('Date desc')
                ->limit(1)
                ->one();

        //统计数据,写入日统计表
        if (empty($errorlogday)) {
            //从第一天开始生成统计记录
            //取ErrorLog的第一条数据,获取开始时间
            $errorlog = ErrorLog::find()
                    ->where("AddDate>0")
                    ->orderBy("AddDate asc")
                    ->limit(1)
                    ->one();
            $add_date = $errorlog->AddDate;
            $str_add_date = strtotime(date("Y-m-d", strtotime($add_date)));
            self::saveErrorLogDay($str_add_date, $appname_list);
        } else {
            //从日统计表最后的date+1天统计数据
            $last_time = strtotime($errorlogday->Date);
            if ($last_time < strtotime(date("Y-m-d"))) {
                $str_add_date = $last_time + 86400;
                self::saveErrorLogDay($str_add_date, $appname_list);
            }
        }

        //显示数据
        $cur_time = strtotime(date("Y-m-d"));
        $day = $page * 5 - 4;
        $count_time = strtotime("{$day} day", $cur_time);

        if (!empty($search_date)) {
            $search_time = strtotime($search_date);
            $count_time = strtotime("-2 day", $search_time);
        }
        //返回5天内的数据
        $items = self::errorLogItems($count_time, 5, $appname_list, 'day');

        if ((empty($page) || $page > 0) && empty($search_date)) {

            //当天统计错误日志的数量
            $format_cur_time = date("Y-m-d");

            $cur_time_format = date("Y-m-d 0:0:0", time());
            $cur_error_query = new Query();
            $cur_error_query->select("count(id) as total,ApplicationId")
                    ->from("ErrorLog")
                    ->where(["in", "ApplicationId", $appname_list]);

            $cur_error_query->andWhere("AddDate>=:adddate", array(":adddate" => $cur_time_format));

            $cur_error_query->groupBy("ApplicationId");
            $cur_data = $cur_error_query->all();
            foreach ($cur_data as $cur) {
                if (in_array($cur['ApplicationId'], $appname_list)) {
                    $items[$format_cur_time][$cur['ApplicationId']] = intval($cur['total']);
                }
            }
        }

        return array("items" => $items, "appnames" => $appname_list);
    }

}

?>