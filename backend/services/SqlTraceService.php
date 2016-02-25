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
        $baseDate=$search_date;
        if($page!=0){
            $search_date = date('Y-m-d', strtotime("$search_date $page day"));
        }
        //$search_date = date("2016-1-4");
        #定义每秒访问的数据，如果是当前查询，就当前的数据为准,否则为86400
        $secend = 86400;
        #如果是今天的数据，进行实时查询
        if (strtotime($search_date) === strtotime(date("Y-m-d"))) {
            $secend = time() - strtotime(date("Y-m-d"));
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
                \Yii::$app->db->createCommand('insert into `SqlTrace_day`(`databasetype`,`Number`,`Date`,Updatetime,`totoltime`) select databasetype,count(0),date_format(executedate,"%Y-%m-%d %H:00:00") Date,now(),sum(sqlusedtime)
                                              from SqlTrace where executedate between :exdate and :exdate1 group by databasetype, date_format(executedate,"%Y-%m-%d %H:00:00")  having databasetype is not null order by null
                                              ', [':exdate' => $search_date, ':exdate1' => date('Y-m-d', strtotime("$search_date +1 day"))])->execute();

                $application_list = $application_query->all();
            }
        }
        if (empty($application_list)) {
            return [];
        }
        #处理数据
        $dabasetype = [];
        $dabasetTotalValue = [];
        $secondVistValue = [];
        $line24Visit = [];
        $line24Visitsec = [];
        $line24Time = [];
        $line24Timesec = [];
        #处理总统计和总的访问频率
        foreach ($application_list as $oneDate) {
            #如果已经存在数组里不存在这个类型,把当前的类型添加进去
            if (!in_array($oneDate['databasetype'], $dabasetype)) {
                array_push($dabasetype, $oneDate['databasetype']);
                $dabasetTotalValue[$oneDate['databasetype']] = round(floatval($oneDate['Number']),5);
                $secondVistValue[$oneDate['databasetype']] = round(floatval($oneDate['Number'] / $secend),5);
                #截取小时
                $h = date("H", strtotime($oneDate['Date']));
                $h = intval($h);
                $line24Visit[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number']),5);
                $line24Visitsec[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number'] / 3600),5);
                $line24Time[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime']),5);
                $line24Timesec[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime'] / 3600),5);
            } else {
                $dabasetTotalValue[$oneDate['databasetype']]+=round(floatval($oneDate['Number']),5);
                $secondVistValue[$oneDate['databasetype']]+=round(floatval($oneDate['Number'] / $secend),5);
                #截取小时
                $h = date("H", strtotime($oneDate['Date']));
                $h = intval($h);
                $line24Visit[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number']),5);
                $line24Visitsec[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number'] / 3600),5);
                $line24Time[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime']),5);
                $line24Timesec[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime'] / 3600),5);
            }
        }
        #处理24小时的数据
        $hourshow = [];
        for ($index = 0; $index < 24; $index++) {
            array_push($hourshow, strval($index));
            foreach ($dabasetype as $onedatavalue) {
                if (!isset($line24Visit[$onedatavalue][$index])) {
                    $line24Visit[$onedatavalue][$index] = 0;
                }
                if (!isset($line24Visitsec[$onedatavalue][$index])) {
                    $line24Visitsec[$onedatavalue][$index] = 0;
                }
                if (!isset($line24Time[$onedatavalue][$index])) {
                    $line24Time[$onedatavalue][$index] = 0;
                }
                if (!isset($line24Timesec[$onedatavalue][$index])) {
                    $line24Timesec[$onedatavalue][$index] = 0;
                }
                #如果到24时，对数据进行顺序排序
                if ($index == 23) {
                    ksort($line24Visit[$onedatavalue]);
                    ksort($line24Visitsec[$onedatavalue]);
                    ksort($line24Time[$onedatavalue]);
                    ksort($line24Timesec[$onedatavalue]);
                }
            }
        }
        $reline24Visit = [];
        $reline24VisitSc = [];
        $reline24Time = [];
        $reline24Timesec = [];
        foreach ($line24Visit as $key => $line24ViValue) {
            $newline24 = [
                'name' => $key,
                'data' => $line24ViValue,
            ];
            array_push($reline24Visit, $newline24);
        }
        foreach ($line24Visitsec as $key => $line24ViValue) {
            $newline24 = [
                'name' => $key,
                'data' => $line24ViValue,
            ];
            array_push($reline24VisitSc, $newline24);
        }
        foreach ($line24Time as $key => $line24ViValue) {
            $newline24 = [
                'name' => $key,
                'data' => $line24ViValue,
            ];
            array_push($reline24Time, $newline24);
        }
        foreach ($line24Timesec as $key => $line24ViValue) {
            $newline24 = [
                'name' => $key,
                'data' => $line24ViValue,
            ];
            array_push($reline24Timesec, $newline24);
        }
        return [
            "search_date" => $search_date,
            "data" => [
                'totalVisit' => array_values($dabasetTotalValue),
                'totalsecondVisit' => array_values($secondVistValue),
                'hourshow' => $hourshow,
                'reline24Visit' => $reline24Visit,
                'reline24VisitSc' => $reline24VisitSc,
                'reline24Time' => $reline24Time,
                'reline24Timesec' => $reline24Timesec
            ],
            "appnames" => $dabasetype
        ];
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