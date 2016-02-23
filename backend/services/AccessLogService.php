<?php

namespace backend\services;
use common\models\AccessLog;


/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class AccessLogService {

    public static function saveToDbForNginx($content_arr){
        $access_log_arr = [] ;
        $num = 0 ;
        foreach($content_arr as $c_val){
            $mat = ToolService::parseNginxAccessLog($c_val) ;

            $user_ip1 = empty($mat[1][0])?"":$mat[1][0] ;
            $user_ip2 = empty($mat[2][0])?"":$mat[2][0] ;
            $user_ip3 = empty($mat[3][0])?"":$mat[3][0] ;
            $user_ip4 = empty($mat[4][0])?"":$mat[4][0] ;
            $request_time = empty($mat[5][0])?"":$mat[5][0] ;

            $request_info = empty($mat[6][0])?"":$mat[6][0] ;//再解析
            $request_mat = ToolService::parseRequestInfo($request_info) ;
            $request_type = empty($request_mat[1][0])?"":$request_mat[1][0] ;
            $access_address =  empty($request_mat[2][0])?"":$request_mat[2][0] ;
            $protocol =  empty($request_mat[3][0])?"":$request_mat[3][0] ;

            $status = empty($mat[7][0])?"":$mat[7][0] ;
            $content_size = empty($mat[8][0])?"":$mat[8][0] ;
            $http_referer = empty($mat[9][0])?"":$mat[9][0] ;

            $user_agent = empty($mat[10][0])?"":$mat[10][0] ;

            $ua = new UserAgentService($user_agent) ;
            $plat_form = $ua->platform() ;
            $browser = $ua->browser() ;

            $take_time = empty($mat[11][0])?"":$mat[11][0] ;

            $access_log_arr[] = [
                $user_ip1,$user_ip2,$user_ip3,$user_ip4,$request_time,$request_type,$protocol,$access_address,
                $status,$content_size,$http_referer,$user_agent,$plat_form,$browser,$take_time
            ] ;

            //每500条批量入库
            if($num>500){
                self::batchSaveNginxAccessLog($access_log_arr) ;
                $access_log_arr = [] ;
                $num = 0 ;
            }
            $num = $num + 1 ;

        }
        self::batchSaveNginxAccessLog($access_log_arr) ;
        return true ;
    }

    //入库
    private static function batchSaveNginxAccessLog($access_log_arr){
        if(!empty($access_log_arr)){
            $command = \Yii::$app->db->createCommand() ;
            $command->batchInsert(
                AccessLog::tableName(),
                [
                    'UserIP1','UserIP2','UserIP3','UserIP4','RequestTime','RequestType','Protocol','AccessAddress' ,
                    'Status','ContentSize','HttpReferer','ClientType','System','Browser','TakeTime'
                ],
                $access_log_arr) ;
            $command->execute();
        }
    }

    public static function saveToDbForIis($content_arr){
        $access_log_arr = [] ;
        $num = 0 ;
        foreach($content_arr as $c_val){
            $note_parse_rs = ToolService::parseIisNote($c_val) ;
            if($note_parse_rs==true){
                //如有注释行,直接读下一行,进行一下次循环
                continue ;
            }
            $mat = ToolService::parseIisAccessLog($c_val) ;
        }
    }
}

?>