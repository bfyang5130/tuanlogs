<?php

namespace backend\services;

use common\models\AccessLog;
use common\models\AccessStatistic;
use common\models\AccessStatisticOne;
use common\models\IisAccessLog;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class AccessLogService {

    /**
     * 分析nginx日志
     * @param $content_arr
     * @param bool|false $cdn_tag
     * @param string $short_name
     * @param string $source
     * @return array
     */
    public static function analyForNginx($content_arr, $cdn_tag = false, $short_name = '', $source = '', $endDateNumFit = false, $str_check_time = 0, $preArray = array(), $writeLine = 0, $end_num_cache_name = '',$step=true) {

        //如果存在上一次处理留下来的数据，那么数据继续跑跑跑。
        if (!empty($preArray)) {
            $access_statistic_arr = $preArray['access_statistic_arr']; //--
            $request_type_arr = $preArray['request_type_arr']; //--
            $protocol_arr = $preArray['protocol_arr']; //--
            $user_ip1_city_arr = $preArray['user_ip1_city_arr']; //--
            $user_ip1_province_arr = $preArray['user_ip1_province_arr']; //--
            $mobile_arr = $preArray['mobile_arr']; //--
            $plat_form_arr = $preArray['plat_form_arr']; //--
            $browser_arr = $preArray['browser_arr']; //--
            $status_arr = $preArray['status_arr']; //--
            $total_content_size = $preArray['total_content_size'];
            $total_take_time = $preArray['total_take_time'];
        } else {
            $access_statistic_arr = []; //--
            $request_type_arr = []; //--
            $protocol_arr = []; //--
            $user_ip1_city_arr = []; //--
            $user_ip1_province_arr = []; //--
            $mobile_arr = []; //--
            $plat_form_arr = []; //--
            $browser_arr = []; //--
            $status_arr = []; //--
            $total_content_size = 0; //--
            $total_take_time = 0; //--
        }
        $num = 0;
        //计算总共的行数
        $content_arrCount = count($content_arr);
        //开始循环处理每一行的信息
        $content_arr_count = 1;
        foreach ($content_arr as $c_val) {
            $parse_rs = ToolService::parseNginxAccessLog($c_val, $cdn_tag);

            $mat = $parse_rs['mat'];

            $china_cache_rs = $parse_rs['china_cache_rs'];

            $ip_mat = ToolService::parseIp($mat[1][0]);

            $user_ip1 = empty($ip_mat[0][0]) ? "" : $ip_mat[0][0];
            $user_ip2 = empty($ip_mat[0][1]) ? "" : $ip_mat[0][1];
            $user_ip3 = empty($ip_mat[0][2]) ? "" : $ip_mat[0][2];
            $user_ip4 = empty($ip_mat[0][3]) ? "" : $ip_mat[0][3];

            //处理时间
            $time = empty($mat[2][0]) ? "" : $mat[2][0];
            if (empty($time)) {
                $request_time = null;
                //时间为空，那么就是当前这条有问题，直接跳过分析
                //记录读到的行数，用来做异常处理
                $writeLine++;
                continue;
            } else {
                $request_time = ToolService::parseNginxDateTime($time);
            }
            //处理当前时间为对应的数值
            //对上一次10分钟处理的时间进行判断处理
            if ($str_check_time == 0) {
                //获得对应的分钟
                $minshow = ToolService::getTenMinute(strtotime($request_time));
                $str_check_time = strtotime(date("Y-m-d H:$minshow:00", strtotime($request_time)));
            }
            //处理当前的时间
            //获得对应的分钟
            $minshow = ToolService::getTenMinute(strtotime($request_time));
            $thisdateTime = strtotime(date("Y-m-d H:$minshow:00", strtotime($request_time)));
            //另外的10分钟已经开始数据开始入库
            if ($thisdateTime > $str_check_time) {
                //----------------------------
                //todoing..............something这里有一个坑，如果没有数据的时候，做一下判断
                //每10分入库
                $end_format_time = date("Y-m-d H:i:s", $str_check_time);
                $access_statistic_arr = self::arrDeal($request_type_arr, $status_arr, $protocol_arr, $plat_form_arr, $mobile_arr, $browser_arr, $user_ip1_province_arr, $user_ip1_city_arr, $total_content_size, $total_take_time, $end_format_time, $short_name);
                if ($source == '21') {
                    self::batchSaveAccessStatistic($access_statistic_arr, $writeLine, $end_num_cache_name,$end_format_time);
                } else {
                    self::batchSaveAccessStatisticOne($access_statistic_arr, $writeLine, $end_num_cache_name,$end_format_time);
                }
                $access_statistic_arr = [];
                //10分钟过后,那么开始检查的10分钟更改为装的10分钟
                $str_check_time = $thisdateTime;
            }
            $request_info = empty($mat[3][0]) ? "" : $mat[3][0]; //再解析
            $request_mat = ToolService::parseRequestInfo($request_info);
            $request_type = empty($request_mat[1][0]) ? "" : $request_mat[1][0];
            $access_address = empty($request_mat[2][0]) ? "" : $request_mat[2][0];
            $protocol = empty($request_mat[3][0]) ? "" : $request_mat[3][0];

            $status = empty($mat[4][0]) ? 0 : $mat[4][0];
            $content_size = empty($mat[5][0]) ? 0 : $mat[5][0];


            if ($china_cache_rs == false) {
                $http_referer = empty($mat[6][0]) ? "" : $mat[6][0];

                $user_agent = empty($mat[7][0]) ? "" : $mat[7][0];

                $ua = new UserAgentService($user_agent);
                $plat_form = $ua->platform();
                $browser = $ua->browser();
                $mobile = $ua->mobile();

                $take_time = empty($mat[8][0]) ? 0 : $mat[8][0];
            } else {
                //有china_cache
                $http_referer = empty($mat[6][0]) ? "" : $mat[6][0];
                $user_agent = empty($mat[7][0]) ? "" : $mat[7][0];
                $plat_form = "";
                $browser = "";
                $mobile = "";
                $take_time = empty($mat[8][0]) ? 0 : $mat[8][0];
            }

            //请求方式
            if (empty($request_type_arr[$request_type]) && !isset($request_type_arr[$request_type])) {
                $request_type_arr[$request_type] = 1;
            } else {
                $request_type_arr[$request_type] += 1;
            }

            //状态
            if (empty($status_arr[$status]) && !isset($status_arr[$status])) {
                $status_arr[$status] = 1;
            } else {
                $status_arr[$status] += 1;
            }

            //协议
            if (empty($protocol_arr[$protocol]) && !isset($protocol_arr[$protocol])) {
                $protocol_arr[$protocol] = 1;
            } else {
                $protocol_arr[$protocol] += 1;
            }

            //使用系统  空时为chinacache
            //
            if (!empty($plat_form) && $plat_form !== 'Unknown Platform') {
                if ($plat_form == 'iOS') {
                    if (empty($mobile_arr[$plat_form][$mobile]) && !isset($mobile_arr[$plat_form][$mobile])) {
                        $mobile_arr[$plat_form][$mobile] = 1;
                    } else {
                        $mobile_arr[$plat_form][$mobile] += 1;
                    }
                } else {
                    if (empty($plat_form_arr[$plat_form]) && !isset($protocol_arr[$plat_form])) {
                        $plat_form_arr[$plat_form] = 1;
                    } else {
                        $plat_form_arr[$plat_form] += 1;
                    }
                };
            }

            //使用浏览器  空时为chinacache
            if (!empty($browser)) {
                if (empty($browser_arr[$browser]) && !isset($browser_arr[$browser])) {
                    $browser_arr[$browser] = 1;
                } else {
                    $browser_arr[$browser] += 1;
                }
            }

            //IP
            if (!empty($user_ip1)) {
                $ip_address = ToolService::getIpAddress($user_ip1);
                $province = $ip_address['province'];
                $city = $ip_address['city'];

                if (!empty($city)) {
                    if (empty($user_ip1_city_arr[$province][$city]) && !isset($user_ip1_city_arr[$province][$city])) {
                        $user_ip1_city_arr[$province][$city] = 1;
                    } else {
                        $user_ip1_city_arr[$province][$city] += 1;
                    }
                } else {
                    if (empty($user_ip1_province_arr[$province]) && !isset($user_ip1_province_arr[$province])) {
                        $user_ip1_province_arr[$province] = 1;
                    } else {
                        $user_ip1_province_arr[$province] += 1;
                    }
                }
            }

            //内容大小
            $total_content_size += $content_size;

            //花费时间
            $total_take_time += $take_time;

            $num = $num + 1;
            $content_arr_count++;
            $writeLine++;
        }
        //最近的一部分数据走完之后，还没有达到10钟的条件
        //如果已经走完所有的数据并且不是步增(实时数据，如果处理前一天的数据就不是步增的，也就是说可以把余下的数据入库,如果是实时的，无法判断这一部分数据是那个区间段的),，那么直接进行入库处理
        if ($endDateNumFit == true&&$step==FALSE) {
            //最近的数据
            if (!empty($total_content_size) && !empty($total_take_time)) {
                $end_format_time = date("Y-m-d H:i:s", $str_check_time);
                $access_statistic_arr = self::arrDeal($request_type_arr, $status_arr, $protocol_arr, $plat_form_arr, $mobile_arr, $browser_arr, $user_ip1_province_arr, $user_ip1_city_arr, $total_content_size, $total_take_time, $end_format_time, $short_name);

                if ($source == '21') {
                    self::batchSaveAccessStatistic($access_statistic_arr, $writeLine, $end_num_cache_name,$end_format_time);
                } else {
                    self::batchSaveAccessStatisticOne($access_statistic_arr, $writeLine, $end_num_cache_name,$end_format_time);
                }
                $access_statistic_arr = [];
            }
            return [];
        } else {
            //当前部分数据走完
            //返回这部分数据待下次再进行处理
            //doing some thing here ..........
            $preArray['access_statistic_arr'] = $access_statistic_arr; //--
            $preArray['request_type_arr'] = $request_type_arr; //--
            $preArray['protocol_arr'] = $protocol_arr; //--
            $preArray['user_ip1_city_arr'] = $user_ip1_city_arr; //--
            $preArray['user_ip1_province_arr'] = $user_ip1_province_arr; //--
            $preArray['mobile_arr'] = $mobile_arr; //--
            $preArray['plat_form_arr'] = $plat_form_arr; //--
            $preArray['browser_arr'] = $browser_arr; //--
            $preArray['status_arr'] = $status_arr; //--
            $preArray['total_content_size'] = $total_content_size;
            $preArray['total_take_time'] = $total_take_time;
            return [
                'leaveDate' => $preArray,
                'str_check_time' => $str_check_time
            ];
        }
    }

    public static function saveToDbForNginx($content_arr, $cdn_tag = false, $short_name = '', $source = '') {
        $access_log_arr = [];
        $num = 0;
        foreach ($content_arr as $c_val) {
            $parse_rs = ToolService::parseNginxAccessLog($c_val, $cdn_tag);

            $mat = $parse_rs['mat'];
            $china_cache_rs = $parse_rs['china_cache_rs'];

            $ip_mat = ToolService::parseIp($mat[1][0]);

            $user_ip1 = empty($ip_mat[0][0]) ? "" : $ip_mat[0][0];
            $user_ip2 = empty($ip_mat[0][1]) ? "" : $ip_mat[0][1];
            $user_ip3 = empty($ip_mat[0][2]) ? "" : $ip_mat[0][2];
            $user_ip4 = empty($ip_mat[0][3]) ? "" : $ip_mat[0][3];

            //处理时间
            $time = empty($mat[2][0]) ? "" : $mat[2][0];
            if (empty($time)) {
                $request_time = null;
            } else {
                $request_time = ToolService::parseNginxDateTime($time);
            }

            $request_info = empty($mat[3][0]) ? "" : $mat[3][0]; //再解析
            $request_mat = ToolService::parseRequestInfo($request_info);
            $request_type = empty($request_mat[1][0]) ? "" : $request_mat[1][0];
            $access_address = empty($request_mat[2][0]) ? "" : $request_mat[2][0];
            $protocol = empty($request_mat[3][0]) ? "" : $request_mat[3][0];

            $status = empty($mat[4][0]) ? 0 : $mat[4][0];
            $content_size = empty($mat[5][0]) ? 0 : $mat[5][0];


            if ($china_cache_rs == false) {
                $http_referer = empty($mat[6][0]) ? "" : $mat[6][0];

                $user_agent = empty($mat[7][0]) ? "" : $mat[7][0];

                $ua = new UserAgentService($user_agent);
                $plat_form = $ua->platform();
                $browser = $ua->browser();

                $take_time = empty($mat[8][0]) ? 0 : $mat[8][0];
            } else {
                //有china_cache
                $http_referer = empty($mat[6][0]) ? "" : $mat[6][0];
                $user_agent = empty($mat[7][0]) ? "" : $mat[7][0];
                $plat_form = "";
                $browser = "";
                $take_time = empty($mat[8][0]) ? 0 : $mat[8][0];
            }


            $access_log_arr[] = [
                $user_ip1, $user_ip2, $user_ip3, $user_ip4, $request_time, $request_type, $protocol, $access_address,
                $status, $content_size, $http_referer, $user_agent, $plat_form, $browser, $take_time, $short_name, $source
            ];
        }
        self::batchSaveNginxAccessLog($access_log_arr);
        return true;
    }

    //入库
    private static function batchSaveNginxAccessLog($access_log_arr) {
        if (!empty($access_log_arr)) {
            $command = \Yii::$app->db->createCommand();
            $command->batchInsert(
                    AccessLog::tableName(), [
                'UserIP1', 'UserIP2', 'UserIP3', 'UserIP4', 'RequestTime', 'RequestType', 'Protocol', 'AccessAddress',
                'Status', 'ContentSize', 'HttpReferer', 'ClientType', 'System', 'Browser', 'TakeTime', 'access_type', 'source'
                    ], $access_log_arr);
            $command->execute();
            unset($access_log_arr);
            $command = null;
        }
    }

    public static function saveToDbForIis($content_arr) {
        $access_log_arr = [];
        $num = 0;
        foreach ($content_arr as $c_val) {
            $note_parse_rs = ToolService::parseIisNote($c_val);
            if ($note_parse_rs == true) {
                //如有注释行,直接读下一行,进行一下次循环
                continue;
            }
            $mat = ToolService::parseIisAccessLog($c_val);
            $request_date = empty($mat[1][0]) ? "" : $mat[1][0];
            $request_time = empty($mat[2][0]) ? "" : $mat[2][0];
            $request_datetime = $request_date . " " . $request_time;

            $server_ip = empty($mat[3][0]) ? "" : $mat[3][0];
            $request_type = empty($mat[4][0]) ? "" : $mat[4][0];

            $cs_url_stem = empty($mat[5][0]) ? "" : $mat[5][0];
            $cs_url_query = empty($mat[6][0]) ? "" : $mat[6][0];

            $server_port = empty($mat[7][0]) ? "" : $mat[7][0];
            $cs_username = empty($mat[8][0]) ? "" : $mat[8][0];
            $client_ip = empty($mat[9][0]) ? "" : $mat[9][0];

            $user_agent = empty($mat[10][0]) ? "" : $mat[10][0];
            $ua = new UserAgentService($user_agent);
            $system = $ua->platform();
            $browser = $ua->browser();

            $status = empty($mat[11][0]) ? "" : $mat[11][0];
            $sub_status = empty($mat[12][0]) ? "" : $mat[12][0];
            $w32_status = empty($mat[13][0]) ? "" : $mat[13][0];

            $time_taken = empty($mat[14][0]) ? "" : $mat[14][0];

            $access_log_arr[] = [
                $request_datetime, $server_ip, $request_type, $cs_url_stem, $cs_url_query, $server_port, $cs_username,
                $client_ip, $user_agent, $system, $browser, $status, $sub_status, $w32_status, $time_taken
            ];

            //每500条批量入库
            if ($num > 500) {
                self::batchSaveIisAccessLog($access_log_arr);
                $access_log_arr = [];
                $num = 0;
            }
            $num = $num + 1;
        }
        self::batchSaveIisAccessLog($access_log_arr);
        return true;
    }

    //入库
    private static function batchSaveIisAccessLog($access_log_arr) {
        if (!empty($access_log_arr)) {
            $command = \Yii::$app->db->createCommand();
            $command->batchInsert(
                    IisAccessLog::tableName(), [
                'RequestTime', 'ServerIp', 'RequestType', 'CsUriStem', 'CsUriQuery', 'ServerPort', 'CsUsername', 'ClientIp',
                'UserAgent', 'System', 'Browser', 'Status', 'SubStatus', 'ScWin32Status', 'TimeTaken',
                    ], $access_log_arr);
            $command->execute();
        }
    }

    //入AccessStatistic库
    private static function batchSaveAccessStatistic($access_statistic_arr, $writeLine, $end_num_cache_name,$checktime) {
        if (!empty($access_statistic_arr)) {
            try {
                $command = \Yii::$app->db->createCommand();
                $command->batchInsert(
                        AccessStatistic::tableName(), [
                    'CheckTime', 'TopType', 'DetailType1', 'DetailType2', 'Amount', 'LogType',
                        ], $access_statistic_arr);
                $abc = $command->execute();
                \Yii::$app->cache->set($end_num_cache_name, $writeLine);
                echo "当前处理到";
                echo "\n";
                echo $writeLine;
                echo "\n";
                echo $checktime;
                echo "\n";
                echo $abc;
                echo "\n";
            } catch (yii\db\Exception $e) {
                //记录读到的最后一行,没有处理的行会被后退
                print_r($e);
                exit;
            }
        }
    }

    //入AccessStatisticOne库
    private static function batchSaveAccessStatisticOne($access_statistic_arr, $writeLine, $end_num_cache_name,$checktime) {
        if (!empty($access_statistic_arr)) {
            try {
                $command = \Yii::$app->db->createCommand();
                $command->batchInsert(
                        AccessStatisticOne::tableName(), [
                    'CheckTime', 'TopType', 'DetailType1', 'DetailType2', 'Amount', 'LogType',
                        ], $access_statistic_arr);
                $abc = $command->execute();
                \Yii::$app->cache->set($end_num_cache_name, $writeLine);
                echo "当前处理到";
                echo "\n";
                echo $writeLine;
                echo "\n";
                echo $checktime;
                echo "\n";
                echo $abc;
                echo "\n";
            } catch (yii\db\Exception $e) {
                //记录读到的最后一行,没有处理的行会被后退
                print_r($e);
                exit;
            }
        }
    }

    private static function arrDeal(&$request_type_arr, &$status_arr, &$protocol_arr, &$plat_form_arr, &$mobile_arr, &$browser_arr, &$user_ip1_province_arr, &$user_ip1_city_arr, &$total_content_size, &$total_take_time, $check_time, $short_name) {
        //请求方式
        foreach ($request_type_arr as $r_key => $r_val) {
            $access_statistic_arr[] = [
                $check_time, "request_type", $r_key, '', $r_val, $short_name
            ];
        }
        $request_type_arr = [];

        //状态
        foreach ($status_arr as $s_key => $s_val) {
            $access_statistic_arr[] = [
                $check_time, "status", $s_key, '', $s_val, $short_name
            ];
        }
        $status_arr = [];

        //协议
        foreach ($protocol_arr as $p_key => $p_val) {
            $access_statistic_arr[] = [
                $check_time, "protocol", $p_key, '', $p_val, $short_name
            ];
        }
        $protocol_arr = [];

        //使用系统
        foreach ($plat_form_arr as $pf_key => $pf_val) {
            $access_statistic_arr[] = [
                $check_time, "plat_form", $pf_key, '', $pf_val, $short_name
            ];
        }
        $plat_form_arr = [];

        //使用系统
        foreach ($mobile_arr as $m_key => $m_val) {
            foreach ($m_val as $mb_key => $mb_val) {
                $access_statistic_arr[] = [
                    $check_time, "plat_form", $m_key, $mb_key, $mb_val, $short_name
                ];
            }
        }
        $mobile_arr = [];

        foreach ($browser_arr as $b_key => $b_val) {
            $access_statistic_arr[] = [
                $check_time, "browser", $b_key, '', $b_val, $short_name
            ];
        }
        $browser_arr = [];

        //IP-province 只有省份
        foreach ($user_ip1_province_arr as $ip_key => $ip_val) {
            $access_statistic_arr[] = [
                $check_time, "user_ip_1", $ip_key, '', $ip_val, $short_name
            ];
        }
        $user_ip1_province_arr = [];

        //IP-city 有城市 有省份
        foreach ($user_ip1_city_arr as $ic_key => $ic_val_arr) {
            foreach ($ic_val_arr as $c_key => $c_val) {
                $access_statistic_arr[] = [
                    $check_time, "user_ip_1", $ic_key, $c_key, $c_val, $short_name
                ];
            }
        }
        $user_ip1_city_arr = [];


        //内容大小
        $access_statistic_arr[] = [
            $check_time, "content_size", '', '', $total_content_size, $short_name
        ];
        $total_content_size = 0;

        //花费时间
        $access_statistic_arr[] = [
            $check_time, "take_time", '', '', $total_take_time, $short_name
        ];
        $total_take_time = 0;
        return $access_statistic_arr;
    }

}

?>