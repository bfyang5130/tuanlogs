<?php

namespace console\controllers;

use yii\console\Controller;
use backend\services\ZabbixCurlService;
use console\services\AutoZabbixService;

/**
 * 日志处理后台程序
 */
class ZabbixController extends Controller {

    public static $header = ['Content-type: application/json;charset="utf-8"', 'Accept: application/json'];
    public static $hashString = null;

    /**
     * 自动获得昨天的几台服务器的数组并进行处理成48条柱子显示的数据
     */
    public function actionAutoserver(){
        //获得数组库中配有的参数
        AutoZabbixService::fitZabbixYestoday();
    }




    private static function hashString() {
        if (ZabbixController::$hashString === NULL) {
            //配置请求参数
            $url = \Yii::$app->params['zabbixapiurl'];
            $user = \Yii::$app->params['zabbixapiuser'];
            $password = \Yii::$app->params['zabbixapipassword'];
            $postData = [
                'jsonrpc' => '2.0',
                'method' => 'user.login',
                'params' => ['user' => $user, 'password' => $password],
                'id' => 1,
                'auth' => NULL
            ];
            $options = [
                CURLOPT_HTTPHEADER => self::$header
            ];
            $reposeData = ZabbixCurlService::curlPostResult($url, $postData, $options);
            if ($reposeData['status'] === false) {
                return FALSE;
            }
            if (!isset($reposeData['info']->result)) {
                return FALSE;
            }
            ZabbixController::$hashString = $reposeData['info']->result;
        }
        return ZabbixController::$hashString;
    }

    public function actionShit() {
        //Init curl
        /**
         * cpu demo
         * curl -i -X POST -H 'Content-Type: application/json' -d '{"jsonrpc": 
          "2.0","method":"history.get","params":{"history":0,"itemids":["99612"],"time_from":"1410403076.3190279","time_till":"1410489466.6890171","output":"extend"},"auth":
          "4023ceeb084e87211f2373626a7b1ea5","id": 0}' http://192.168.49.90/zabbix/api_jsonrpc.php;
         */
        $hashString = self::hashString();




        if (!$hashString) {
            echo 11;
        }
        //配置请求参数
        
        $endtime = time();
        $starttime = strtotime("-10 min", $endtime);
        $postData = [
            'jsonrpc' => '2.0',
            'method' => 'history.get',
            'params' => [
                'history' => 0,
                'itemids' => 23660,
                'time_from' => $starttime,
                'time_till' => $endtime,
                'output' => 'extend'
            ],
            'id' => 0,
            'auth' => $hashString
        ];
        $reposeData = ZabbixCurlService::curlPostResult($postData);
        if ($reposeData['status'] === false) {
            return FALSE;
        }
        print_r($reposeData['info']);
    }
}

