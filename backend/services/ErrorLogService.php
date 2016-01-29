<?php

namespace backend\services;

use common\models\ApplicateName;
use common\models\ErrorLog;
use yii\data\ActiveDataProvider;
use yii\db\Query;

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

}

?>