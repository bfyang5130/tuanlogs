<?php

namespace backend\services;

use common\models\ApplicateName;
use common\models\ErrorLog;
use common\models\ErrorLogDay;
use common\models\ErrorLogMonth;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use Yii ;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppcationNameService
 *
 * @author Administrator
 */
class ErrorLogService {

    /**
     *
     * @return \yii\data\ActiveDataProvider
     */
    public static function findErrorLogByAppId() {
        $p_get = \Yii::$app->request->get();
        if (!$p_get['id']) {
            $p_get['id'] = 1;
        }
        $model = new ErrorLog();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where("ApplicationId=:appid", [":appid" => $p_get['id']]),
            'pagination' => [
                'pagesize' => 20,
            ]
        ]);
        return $dataProvider;
    }

    /**
     * 按ApplicationId统计错误的数量
     * @return array
     */
    public static function countErrorByApplicationId(){
        //查找Error的appname
        $application_query = new Query() ;
        $application_query->select("appname")
            ->from("ApplicateName")
            ->where("logtype=1") ;//1-Error类型 0-trace
        $application_list = $application_query->all() ;
        $appname_list = array() ;
        foreach($application_list as $value){
            $appname_list[] = $value['appname'] ;
        }
        #获得没有统计在ApplicateName里的错误信息
        #$newApplicateNameType=new Query();
        #$newApplicateNameType->
        #insert into ApplicateName(`appname`,`newname`,`logtotal`,`logtype`,`lastupdatetime`) select ApplicationId,ApplicationId,count(*),1,datetime() from ErrorLog where ApplicationID not in($appname_list) groud by ApplicationId;
        //获得统计日志最后更新时间
        $application_query = new Query() ;
        $application_query->select("lastupdatetime")
            ->from("ApplicateName")
            ->where("logtype=1 and lastupdatetime>0") ;//1-Error类型 0-trace
        $application = $application_query->one() ;
        if(!empty($application)) {
            $lasupdatetime = $application['lastupdatetime'];
            $format_time = $lasupdatetime ;
        }

        //统计错误日志的数量
        $error_query = new Query() ;
        $error_query->select("count(id) as total,ApplicationId")
            ->from("ErrorLog")
            ->where(["in","ApplicationId",$appname_list]) ;

        if(!empty($format_time)){
            $error_query->andWhere("AddDate>:adddate",array(":adddate"=>$format_time)) ;
        }

        $error_query->groupBy("ApplicationId") ;

        $error_count_list = $error_query->all() ;

        $curtime = date("Y-m-d H:i:s") ;
        foreach($error_count_list as $key=>$error_count){
            $applicationId = $error_count['ApplicationId'] ;
            $application = ApplicateName::findOne(["appname"=>$applicationId,"logtype"=>1]);
            if(!empty($application)){
                $application->logtotal = $application->logtotal +$error_count['total'] ;
                $application->lastupdatetime = $curtime ;
                $application->save() ;
//                $error_count_list[$key]['total'] = $application->logtotal ;
            }
        }

        //重新获取ErrorLog的统计数据
        $error_query = new Query() ;
        $error_query->select("count(id) as total,ApplicationId")
            ->from("ErrorLog")
            ->where(["in","ApplicationId",$appname_list])
            ->groupBy("ApplicationId") ;
        $error_count_list = $error_query->all() ;

        return $error_count_list ;
    }

    /**
     * 按天统计错误数量
     */
    public static function countByDay($page,$search_date){
        //设置不超时 首次运行会统计数据,比较慢
        set_time_limit(0) ;
        //查找Error的appname
        $application_query = new Query() ;
        $application_query->select("appname")
            ->from("ApplicateName")
            ->where("logtype=1") ;//1-Error类型 0-trace
        $application_list = $application_query->all() ;
        $appname_list = array() ;
        foreach($application_list as $value){
            $appname_list[] = $value['appname'] ;
        }

        $errorlogday = ErrorLogDay::find()
            ->orderBy('Date desc')
            ->limit(1)
            ->one();

        //统计数据,写入日统计表
        if(empty($errorlogday)){
            //从第一天开始生成统计记录
            //取ErrorLog的第一条数据,获取开始时间
            $errorlog = ErrorLog::find()
                ->where("AddDate>0")
                ->orderBy("AddDate asc")
                ->limit(1)
                ->one() ;
            $add_date = $errorlog->AddDate;
            $str_add_date = strtotime(date("Y-m-d",strtotime($add_date))) ;
            self::saveErrorLogDay($str_add_date,$appname_list) ;
        }else{
            //从日统计表最后的date+1天统计数据
            $last_time = strtotime($errorlogday->Date) ;
            if($last_time<strtotime(date("Y-m-d"))){
                $str_add_date = $last_time+86400 ;
                self::saveErrorLogDay($str_add_date,$appname_list) ;
            }
        }

        //显示数据
        $cur_time = strtotime(date("Y-m-d")) ;
        $day = $page*5-4 ;
        $count_time = strtotime("{$day} day", $cur_time);

        if(!empty($search_date)){
            $search_time = strtotime($search_date) ;
            $count_time = strtotime("-2 day", $search_time);
        }
        //返回5天内的数据
        $items = self::errorLogItems($count_time,5,$appname_list,'day') ;

        if((empty($page) || $page>0) && empty($search_date)){

            //当天统计错误日志的数量
            $format_cur_time = date("Y-m-d") ;

            $cur_time_format = date("Y-m-d 0:0:0",time()) ;
            $cur_error_query = new Query() ;
            $cur_error_query->select("count(id) as total,ApplicationId")
                ->from("ErrorLog")
                ->where(["in","ApplicationId",$appname_list]) ;

            $cur_error_query->andWhere("AddDate>=:adddate",array(":adddate"=>$cur_time_format)) ;

            $cur_error_query->groupBy("ApplicationId") ;
            $cur_data = $cur_error_query->all() ;
            foreach($cur_data as $cur){
                if(in_array($cur['ApplicationId'],$appname_list)){
                    $items[$format_cur_time][$cur['ApplicationId']] = intval($cur['total']) ;
                }
            }

        }

        return array("items"=>$items,"appnames"=>$appname_list) ;

    }

    private static function saveErrorLogDay($str_time,$appname_list){
        $cur_time = time() ;
        $format_cur_time = date("Y-m-d H:i:s",time()) ;

        $end_time = strtotime('+1 day', $str_time);

        $diff_day = ($cur_time-$str_time)/86400 ;//到当前时间相差的天数

        $format_str_time = date("Y-m-d H:i:s",$str_time) ;
        $format_end_time = date("Y-m-d H:i:s",$end_time) ;
        for($i=0;$i<$diff_day;$i++){

            //统计错误日志的数量
            $error_query = new Query() ;
            $error_query->select("count(id) as total,ApplicationId")
                ->from("ErrorLog")
                ->where(["in","ApplicationId",$appname_list]) ;

            $error_query->andWhere("AddDate>=:str_time",array(":str_time"=>$format_str_time)) ;
            $error_query->andWhere("AddDate<:end_time",array(":end_time"=>$format_end_time)) ;

            $error_query->groupBy("ApplicationId") ;

            $error_count_list = $error_query->all() ;

            $log_arr = [] ;
            foreach($error_count_list as $key=>$value){
                //保存数据在ErrorLog_day
                $log_arr[] = [$value['ApplicationId'],$value['total'],$format_str_time,$format_cur_time] ;
            }

            if(!empty($log_arr)){
                $command = \Yii::$app->db->createCommand() ;
                $command->batchInsert(
                    ErrorLogDay::tableName(),
                    ['ApplicationId','Number','Date','Updatetime'],
                    $log_arr) ;
                $command->execute();
            }

            $format_str_time = $format_end_time ;
            $end_time = strtotime('+1 day', $end_time);
            $format_end_time = date("Y-m-d H:i:s",$end_time) ;

        }
    }


    /**
     * 按月统计错误数量
     */
    public static function countByMonth($page){
        //设置不超时 首次运行会统计数据,比较慢
        set_time_limit(0) ;
        //查找Error的appname
        $application_query = new Query() ;
        $application_query->select("appname")
            ->from("ApplicateName")
            ->where("logtype=1") ;//1-Error类型 0-trace
        $application_list = $application_query->all() ;
        $appname_list = array() ;
        foreach($application_list as $value){
            $appname_list[] = $value['appname'] ;
        }

        $errorlogmonth = ErrorLogMonth::find()
            ->orderBy('Month desc')
            ->limit(1)
            ->one();

        //统计数据,写入日统计表
        if(empty($errorlogmonth)){
            //从第一天开始生成统计记录
            //取ErrorLog的第一条数据,获取开始时间
            $errorlog = ErrorLog::find()
                ->where("AddDate>0")
                ->orderBy("AddDate asc")
                ->limit(1)
                ->one() ;
            $add_date = $errorlog->AddDate;
            $str_add_date = strtotime(date("Y-m-01",strtotime($add_date))) ;
            self::saveErrorLogMonth($str_add_date,$appname_list) ;
        }else{
            //从月统计表最后的month+1月统计数据
            $last_time = strtotime(($errorlogmonth->Month)."01") ;

            if($last_time<strtotime(date("Y-m-01"))){
                $str_add_date = strtotime("+1 month",$last_time) ;
                self::saveErrorLogMonth($str_add_date,$appname_list) ;
            }
        }

        //显示数据
        $cur_month = date("m") ;
        if($cur_month>=7){
            $count_time =  strtotime(date("Y-12-01")) ;
        }else{
            $count_time =  strtotime(date("Y-06-01")) ;
        }

        $month = $page*6-5 ;
        $count_time = strtotime("{$month} month", $count_time);

        //返回半年内的数据
        $items = self::errorLogItems($count_time,6,$appname_list,'month') ;

        if(empty($page) || $page>0){
            //当月统计错误日志的数量
            $cur_time_format = date("Y-m-01 0:0:0",time()) ;
            $time_format = date("Y-m-01",time()) ;
            $cur_error_query = new Query() ;
            $cur_error_query->select("count(id) as total,ApplicationId")
                ->from("ErrorLog")
                ->where(["in","ApplicationId",$appname_list]) ;

            $cur_error_query->andWhere("AddDate>=:adddate",array(":adddate"=>$cur_time_format)) ;

            $cur_error_query->groupBy("ApplicationId") ;
            $cur_data = $cur_error_query->all() ;
            foreach($cur_data as $cur){
                if(in_array($cur['ApplicationId'],$appname_list)){
                    $items[$time_format][$cur['ApplicationId']] = intval($cur['total']) ;
                }
            }
        }
        return array("items"=>$items,"appnames"=>$appname_list) ;

    }

    private static function saveErrorLogMonth($str_time,$appname_list){
        $format_cur_time = date("Y-m-d H:i:s",time()) ;
        $cur_time_year = date("Y") ;
        $cur_time_month = date("m") ;

        $str_time_year = date("Y",$str_time) ;
        $str_time_month = date("m",$str_time) ;

        $diff_month = ($cur_time_year-$str_time_year)*12 +($cur_time_month-$str_time_month) ;

        $end_time = strtotime('+1 month', $str_time);

        $format_str_time = date("Y-m-d H:i:s",$str_time) ;
        $format_end_time = date("Y-m-d H:i:s",$end_time) ;

        for($i=0;$i<$diff_month;$i++){

            //统计错误日志的数量
            $error_query = new Query() ;
            $error_query->select("count(id) as total,ApplicationId")
                ->from("ErrorLog")
                ->where(["in","ApplicationId",$appname_list]) ;

            $error_query->andWhere("AddDate>=:str_time",array(":str_time"=>$format_str_time)) ;
            $error_query->andWhere("AddDate<:end_time",array(":end_time"=>$format_end_time)) ;

            $error_query->groupBy("ApplicationId") ;

            $error_count_list = $error_query->all() ;

            $log_arr = [] ;
            $month = strtotime($format_str_time);
            foreach($error_count_list as $key=>$value){
                //保存数据在ErrorLog_month
                $log_arr[] = [$value['ApplicationId'],$value['total'],date("Ym",$month),$format_cur_time] ;
            }

            if(!empty($log_arr)){
                $command = \Yii::$app->db->createCommand() ;
                $command->batchInsert(
                    ErrorLogMonth::tableName(),
                    ['ApplicationId','Number','Month','Updatetime'],
                    $log_arr) ;
                $command->execute();
            }

            $format_str_time = $format_end_time ;
            $end_time = strtotime('+1 month', $end_time);
            $format_end_time = date("Y-m-d H:i:s",$end_time) ;

        }
    }


    private static function errorLogItems($search_time,$times,$appname_list,$add_type){
        $item_arr =array() ;
        for($i=1;$i<=$times;$i++){

            $format_search_time = date("Y-m-d",$search_time) ;

            foreach($appname_list as $appname){
                $item_arr[$format_search_time][$appname] = 0;
            }

            if($add_type=='month'){
                $cur_time_format = date("Ym",$search_time) ;
                $datas = ErrorLogMonth::find()
                    ->where("Month=:cur_time",[":cur_time"=>$cur_time_format])
                    ->orderBy("id asc")
                    ->all();
            }else{
                $format_time = date("Y-m-d H:i:s",$search_time) ;
                $datas = ErrorLogDay::find()
                    ->where(["Date"=>$format_time])
                    ->all();
            }

            foreach($datas as $data){
                if(in_array($data['ApplicationId'],$appname_list)){
                    $item_arr[$format_search_time][$data['ApplicationId']] = $data['Number'] ;
                }
            }

            if($add_type=='month'){
                $search_time = strtotime("+1 month", $search_time);
            }else{
                $search_time = strtotime("+1 day", $search_time);
            }

        }
        return $item_arr ;
    }

    public static function getYearList(){
        //获取错误日志最先的时间
        $errorlog = ErrorLog::find()
            ->where("AddDate>0")
            ->orderBy("AddDate asc")
            ->limit(1)
            ->one() ;
        $add_date = $errorlog->AddDate;
        $str_year = date("Y",strtotime($add_date)) ;
        $cur_year = date("Y") ;
        $cur_month = date("n") ;
        $diff = $cur_year-$str_year +1 ;
        $year = $cur_year ;
        $item =array() ;
        $key = 0 ;
        for($i=0 ; $i<$diff;$i++){
            if($i==0){
                if($cur_month>6) {
                    $item[$key] = $year . "下半年";
                    $key -= 1;
                }
            }else{
                $item[$key] = $year."下半年" ;
                $key -= 1 ;
            }
            $item[$key] = $year."上半年" ;
            $key -= 1 ;
            $year = $year - 1 ;
        }
        return $item ;

    }

}

?>