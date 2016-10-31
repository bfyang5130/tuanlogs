<?php

namespace backend\services;

use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Description of SqlTraceService
 *
 * @author Administrator
 */
class SqlAttackService {

    /**
     * 获得数据库对应数据
     * @return type
     */
    public static function getSqlTraceDbType() {
        $query = new Query();
        $query->select("appname")
                ->from("ApplicateName")
                ->where('logtype=2')
                ->distinct();
        $dbtypes = $query->all();
        $dbtype_item = ArrayHelper::map($dbtypes, "appname", "appname");
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
        $baseDate = $search_date;
        if ($page != 0) {
            $search_date = date('Y-m-d', strtotime("$search_date $page day"));
        }
        //$search_date = date("2016-1-4");
        #定义每秒访问的数据，如果是当前查询，就当前的数据为准,否则为86400
        $secend = 86400;
        //获得数据之前，先做一次统计
        try {
            \Yii::$app->db->createCommand("call p_SqlTrace_day")->execute();
        } catch (\yii\console\Exception $e) {
            
        }
        #如果是今天的数据，设定统计的一些数据做改变
        if (strtotime($search_date) === strtotime(date("Y-m-d"))) {
            $secend = time() - strtotime(date("Y-m-d"));
        }
        #从已经有的数据里取出数据
        $application_query = new Query();
        $application_query->select("*")
                ->from("SqlTrace_day")
                ->where("`Date` between :exdate and :exdate1", [':exdate' => $search_date, ':exdate1' => date('Y-m-d', strtotime("$search_date +1 day"))]);
        $application_list = $application_query->all();
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
                $dabasetTotalValue[$oneDate['databasetype']] = round(floatval($oneDate['Number']), 5);
                $secondVistValue[$oneDate['databasetype']] = round(floatval($oneDate['Number'] / $secend), 5);
                #截取小时
                $h = date("H", strtotime($oneDate['Date']));
                $h = intval($h);
                $line24Visit[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number']), 5);
                $line24Visitsec[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number'] / 3600), 5);
                $line24Time[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime']), 5);
                $line24Timesec[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime'] / 3600), 5);
            } else {
                $dabasetTotalValue[$oneDate['databasetype']]+=round(floatval($oneDate['Number']), 5);
                $secondVistValue[$oneDate['databasetype']]+=round(floatval($oneDate['Number'] / $secend), 5);
                #截取小时
                $h = date("H", strtotime($oneDate['Date']));
                $h = intval($h);
                $line24Visit[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number']), 5);
                $line24Visitsec[$oneDate['databasetype']][$h] = round(floatval($oneDate['Number'] / 3600), 5);
                $line24Time[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime']), 5);
                $line24Timesec[$oneDate['databasetype']][$h] = round(floatval($oneDate['totoltime'] / 3600), 5);
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
    }

    /**
     * 处理最近十分钟的TOP50最慢的SQL不重复
     */
    public static function tofitTop50near10minute() {
        //处理一个太频繁的数据
        $marktime = \Yii::$app->cache->get('top50time');
        $time = time();
        //如果在30秒内的操作不做新的统计如果说看实时的，那只看最近1分钟就差不多了。10分钟卡太多时间了
        if ($marktime && $time < ($marktime + 30)) {
            
        } else {
            $sql = "insert into SqlTrace_top50(executedate,sqltext_md5,sqlusedtime,sqltext,databasetype,update_time)
select s.executedate,m.sqltext_md5,s.sqlusedtime,s.sqltext,s.databasetype,now()
from SqlTrace s 
inner join 
(select sqltext_md5,sqlusedtime,id from 
	(SELECT md5(sqltext) as sqltext_md5,sqlusedtime,id 
	 FROM SqlTrace   where executedate  between date_sub(now(),interval 1 minute) and now()  
	 order by sqlusedtime desc
     ) sql_tmp 
     group by sqltext_md5  order by sqlusedtime desc limit 50
) m on s.id=m.id;";
            \Yii::$app->db->createCommand($sql)->execute();
        }
    }

}

?>