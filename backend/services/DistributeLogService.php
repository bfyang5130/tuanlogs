<?php

namespace backend\services;
use common\models\DistributeLog;


/**
 * Description of DistributeLogService
 *
 * @author Administrator
 */
class DistributeLogService {

    public static function saveToDb($start_num=0,$end_num=0,$file= '',$target='',$source=''){
        $distribut_log = new DistributeLog() ;
        $distribut_log->start_num =$start_num ;
        $distribut_log->end_num = $end_num ;
        $distribut_log->start_time = date("Y-m-d H:i:s") ;
        $distribut_log->statis = 0 ;
        $distribut_log->file = $file ;
        $distribut_log->target = $target ;
        $distribut_log->source = $source ;
        $distribut_log->save() ;
        $id = empty($distribut_log->id)?0:$distribut_log->id ;
        return $id ;
    }

    public static function updateToDb($id){
        $distribut_log = DistributeLog::findOne($id) ;
        if(!empty($distribut_log)){
            $distribut_log->end_time = date("Y-m-d H:i:s") ;
            $distribut_log->statis = 1 ;
            $distribut_log->save() ;
        }
    }

}

?>