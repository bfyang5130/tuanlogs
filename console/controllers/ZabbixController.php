<?php

namespace console\controllers;

use yii\console\Controller;
use backend\services\ZabbixCurlService;

/**
 * 日志处理后台程序
 */
class ZabbixController extends Controller {

    public static $header = ['Content-type: application/json;charset="utf-8"', 'Accept: application/json'];
    public static $hashString = null;

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

//
//    /**
//     * Yii action controller
//     */
//    public function actions() {
//        return [
//            'error' => [
//                'class' => 'yii\web\ErrorAction',
//            ],
//        ];
//    }
//
//    /**
//     * cURL GET example
//     */
//    public function actionGetExample() {
//        //Init curl
//        $curl = new curl\Curl();
//
//        //get http://example.com/
//        $response = $curl->get('http://example.com/');
//    }
//
//    /**
//     * cURL POST example with post body params.
//     */
//    public function actionPostExample() {
//        //Init curl
//        $curl = new curl\Curl();
//
//        //post http://example.com/
//        $response = $curl->setOption(
//                        CURLOPT_POSTFIELDS, http_build_query(array(
//                    'myPostField' => 'value'
//                                )
//                ))
//                ->post('http://example.com/');
//    }
//
//    /**
//     * cURL multiple POST example one after one
//     */
//    public function actionMultipleRequest() {
//        //Init curl
//        $curl = new curl\Curl();
//
//
//        //post http://example.com/
//        $response = $curl->setOption(
//                        CURLOPT_POSTFIELDS, http_build_query(array(
//                    'myPostField' => 'value'
//                                )
//                ))
//                ->post('http://example.com/');
//
//
//        //post http://example.com/, reset request before
//        $response = $curl->reset()
//                ->setOption(
//                        CURLOPT_POSTFIELDS, http_build_query(array(
//                    'myPostField' => 'value'
//                                )
//                ))
//                ->post('http://example.com/');
//    }
//
//    /**
//     * cURL advanced GET example with HTTP status codes
//     */
//    public function actionGetAdvancedExample() {
//        //Init curl
//        $curl = new curl\Curl();
//
//        //get http://example.com/
//        $response = $curl->post('http://www.baidu.com/');
//
//        // List of status codes here http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
//        switch ($curl->responseCode) {
//
//            case 'timeout':
//                //timeout error logic here
//                break;
//
//            case 200:
//                //success logic here
//                break;
//
//            case 404:
//                //404 Error logic here
//                break;
//        }
//    }
//
//    /**
//     * cURL timeout chaining/handling
//     */
//    public function actionHandleTimeoutExample() {
//        //Init curl
//        $curl = new curl\Curl();
//
//        //get http://www.google.com:81/ -> timeout
//        $response = $curl->post('http://www.google.com:81/');
//
//        // List of status codes here http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
//        switch ($curl->responseCode) {
//
//            case 'timeout':
//                //timeout error logic here
//                break;
//
//            case 200:
//                //success logic here
//                break;
//
//            case 404:
//                //404 Error logic here
//                break;
//        }
//    }
}

