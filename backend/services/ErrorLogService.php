<?php

namespace backend\services;

use common\models\ApplicateName;
use common\models\ErrorLog;
use common\models\ErrorLogDay;
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

        //获得统计日志最后更新时间
        $application_query = new Query() ;
        $application_query->select("lastupdatetime")
            ->from("ApplicateName")
            ->where("logtype=1 and lastupdatetime>0") ;//1-Error类型 0-trace
        $application = $application_query->one() ;
        if(!empty($application)) {
            $lasupdatetime = $application['lastupdatetime'];
            $format_time = date("Y-m-d H:i:s",$lasupdatetime) ;
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

        $curtime = time() ;
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
    public static function countByDay($page){
        //设置不超时
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
            $last_time = $errorlogday->Date ;
            if($last_time<strtotime(date("Y-m-d"))){
                $str_add_date = $last_time+86400 ;
                self::saveErrorLogDay($str_add_date,$appname_list) ;
            }
        }

        foreach($appname_list as $appname){
            $before_count[$appname] = 0;
            $cur_count[$appname] = 0;
        }

        if(empty($page) || $page>0){
            //显示日统计数据
            $errorlogday = ErrorLogDay::find()
                ->orderBy('Date desc')
                ->limit(1)
                ->one();
            $before_time = $errorlogday->Date ;

            $before_datas = ErrorLogDay::find()
                ->where(["Date"=>$before_time])
                ->all();

            foreach($before_datas as $before){
                if(in_array($before['ApplicationId'],$appname_list)){
                    $before_count[$before['ApplicationId']] = $before['Number'] ;
                }
            }

            //当天统计错误日志的数量
            $cur_time = date("Y-m-d 0:0:0",time()) ;
            $cur_error_query = new Query() ;
            $cur_error_query->select("count(id) as total,ApplicationId")
                ->from("ErrorLog")
                ->where(["in","ApplicationId",$appname_list]) ;

            $cur_error_query->andWhere("AddDate>=:adddate",array(":adddate"=>$cur_time)) ;

            $cur_error_query->groupBy("ApplicationId") ;
            $cur_data = $cur_error_query->all() ;
            foreach($cur_data as $cur){
                if(in_array($cur['ApplicationId'],$appname_list)){
                    $cur_count[$cur['ApplicationId']] = intval($cur['total']) ;
                }
            }

            $format_before_time = date("Y-m-d",$before_time) ;
            $format_cur_time = date("Y-m-d") ;
        }else{
            $cur_time = strtotime(date("Y-m-d")) ;
            $day = $page*2-1 ;
            $before_time = strtotime("{$day} day", $cur_time);
//            $end_time = strtotime("+1 day", $str_time);
            $before_datas = ErrorLogDay::find()
                ->where("Date=:str_time",[":str_time"=>$before_time])
                ->orderBy("id asc")
                ->all();

            foreach($before_datas as $before){
                if(in_array($before['ApplicationId'],$appname_list)){
                    $before_count[$before['ApplicationId']] = $before['Number'] ;
                }
            }

            $cur_time = strtotime("+1 day", $before_time);
            $cur_datas = ErrorLogDay::find()
                ->where("Date=:cur_time",[":cur_time"=>$cur_time])
                ->orderBy("id asc")
                ->all();

            foreach($cur_datas as $cur){
                if(in_array($cur['ApplicationId'],$appname_list)){
                    $cur_count[$cur['ApplicationId']] = $cur['Number'] ;
                }
            }

            $format_before_time = date("Y-m-d",$before_time) ;
            $format_cur_time = date("Y-m-d",$cur_time) ;

        }

        return ["before_count"=>$before_count,"cur_count"=>$cur_count,"format_before_time"=>$format_before_time,"format_cur_time"=>$format_cur_time] ;
    }

    private static function saveErrorLogDay($str_time,$appname_list){
        $cur_time = time() ;

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
                $log_arr[] = [$value['ApplicationId'],$value['total'],strtotime($format_str_time),$cur_time] ;
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

}

?>