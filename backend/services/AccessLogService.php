<?php

namespace backend\services;
use common\models\AccessLog;
use common\models\IisAccessLog;


/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class AccessLogService {

    public static function saveToDbForNginx($content_arr,$cdn_tag=false,$short_name='',$source=''){
        $access_log_arr = [] ;
        $num = 0 ;
        foreach($content_arr as $c_val){
            $parse_rs = ToolService::parseNginxAccessLog($c_val,$cdn_tag) ;

            $mat = $parse_rs['mat'] ;
            $china_cache_rs = $parse_rs['china_cache_rs'] ;

            $ip_mat = ToolService::parseIp($mat[1][0]) ;

            $user_ip1 = empty($ip_mat[0][0])?"":$ip_mat[0][0] ;
            $user_ip2 = empty($ip_mat[0][1])?"":$ip_mat[0][1] ;
            $user_ip3 = empty($ip_mat[0][2])?"":$ip_mat[0][2] ;
            $user_ip4 = empty($ip_mat[0][3])?"":$ip_mat[0][3] ;

            //处理时间
            $time = empty($mat[2][0])?"":$mat[2][0] ;
            if(empty($time)){
                $request_time = null ;
            }else{
                $request_time =ToolService::parseNginxDateTime($time) ;
            }

            $request_info = empty($mat[3][0])?"":$mat[3][0] ;//再解析
            $request_mat = ToolService::parseRequestInfo($request_info) ;
            $request_type = empty($request_mat[1][0])?"":$request_mat[1][0] ;
            $access_address =  empty($request_mat[2][0])?"":$request_mat[2][0] ;
            $protocol =  empty($request_mat[3][0])?"":$request_mat[3][0] ;

            $status = empty($mat[4][0])?0:$mat[4][0] ;
            $content_size = empty($mat[5][0])?0:$mat[5][0] ;


            if($china_cache_rs==false){
                $http_referer = empty($mat[6][0])?"":$mat[6][0] ;

                $user_agent = empty($mat[7][0])?"":$mat[7][0] ;

                $ua = new UserAgentService($user_agent) ;
                $plat_form = $ua->platform() ;
                $browser = $ua->browser() ;

                $take_time = empty($mat[8][0])?0:$mat[8][0] ;
            }else{
                //有china_cache
                $http_referer = empty($mat[6][0])?"":$mat[6][0] ;
                $user_agent = empty($mat[7][0])?"":$mat[7][0] ;
                $http_x_forward_for =
                $plat_form = "" ;
                $browser ="" ;
                $take_time = empty($mat[8][0])?0:$mat[8][0] ;
            }


            $access_log_arr[] = [
                $user_ip1,$user_ip2,$user_ip3,$user_ip4,$request_time,$request_type,$protocol,$access_address,
                $status,$content_size,$http_referer,$user_agent,$plat_form,$browser,$take_time,$short_name,$source
            ] ;

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
                    'Status','ContentSize','HttpReferer','ClientType','System','Browser','TakeTime','access_type','source'
                ],
                $access_log_arr) ;
            $command->execute();
            unset($access_log_arr) ;
            $command = null ;
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
            $request_date = empty($mat[1][0])?"":$mat[1][0] ;
            $request_time = empty($mat[2][0])?"":$mat[2][0] ;
            $request_datetime = $request_date." ".$request_time ;

            $server_ip = empty($mat[3][0])?"":$mat[3][0] ;
            $request_type = empty($mat[4][0])?"":$mat[4][0] ;

            $cs_url_stem = empty($mat[5][0])?"":$mat[5][0] ;
            $cs_url_query = empty($mat[6][0])?"":$mat[6][0] ;

            $server_port = empty($mat[7][0])?"":$mat[7][0] ;
            $cs_username = empty($mat[8][0])?"":$mat[8][0] ;
            $client_ip =  empty($mat[9][0])?"":$mat[9][0] ;

            $user_agent = empty($mat[10][0])?"":$mat[10][0] ;
            $ua = new UserAgentService($user_agent) ;
            $system = $ua->platform() ;
            $browser = $ua->browser() ;

            $status = empty($mat[11][0])?"":$mat[11][0] ;
            $sub_status = empty($mat[12][0])?"":$mat[12][0] ;
            $w32_status = empty($mat[13][0])?"":$mat[13][0] ;

            $time_taken =empty($mat[14][0])?"":$mat[14][0] ;

            $access_log_arr[] = [
                $request_datetime,$server_ip,$request_type,$cs_url_stem,$cs_url_query,$server_port,$cs_username,
                $client_ip,$user_agent,$system,$browser,$status,$sub_status,$w32_status,$time_taken
            ] ;

            //每500条批量入库
            if($num>500){
                self::batchSaveIisAccessLog($access_log_arr) ;
                $access_log_arr = [] ;
                $num = 0 ;
            }
            $num = $num + 1 ;
        }
        self::batchSaveIisAccessLog($access_log_arr) ;
        return true ;
    }

    //入库
    private static function batchSaveIisAccessLog($access_log_arr){
        if(!empty($access_log_arr)){
            $command = \Yii::$app->db->createCommand() ;
            $command->batchInsert(
                IisAccessLog::tableName(),
                [
                    'RequestTime','ServerIp','RequestType','CsUriStem','CsUriQuery','ServerPort','CsUsername','ClientIp' ,
                    'UserAgent','System','Browser','Status','SubStatus','ScWin32Status','TimeTaken',
                ],
                $access_log_arr) ;
            $command->execute();
        }
    }
}

?>