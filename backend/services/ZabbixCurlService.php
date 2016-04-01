<?php

namespace backend\services;

use linslin\yii2\curl\Curl;
use Yii;

/**
 * Description of ToolService
 *
 * @author Administrator
 */
class ZabbixCurlService {

    public static $header = ['Content-type: application/json;charset="utf-8"', 'Accept: application/json'];
    public static $hashString = null;

    public static function hashString() {
        if (ZabbixCurlService::$hashString === NULL) {
            //配置请求参数
            $user = \Yii::$app->params['zabbixapiuser'];
            $password = \Yii::$app->params['zabbixapipassword'];
            $postData = [
                'jsonrpc' => '2.0',
                'method' => 'user.login',
                'params' => ['user' => $user, 'password' => $password],
                'id' => 1
            ];
            $reposeData = ZabbixCurlService::curlPostResult($postData, TRUE);
            if ($reposeData['status'] === false) {
                return FALSE;
            }
            if (!isset($reposeData['info']->result)) {
                return FALSE;
            }
            ZabbixCurlService::$hashString = $reposeData['info']->result;
        }
        return ZabbixCurlService::$hashString;
    }

    /**
     * 通过curl POST数据后获得信息
     * @return type
     */
    public static function curlPostResult($postData, $auth = false) {
        if ($auth === FALSE) {
            if (ZabbixCurlService::hashString() === FALSE) {
                return ['status' => FALSE, 'error' => 'Auth incorrect'];
            }

            $postData['auth'] = ZabbixCurlService::$hashString;
        } else {
            $postData['auth'] = null;
        }
        //Init curl
        $curl = new Curl();
        $options = [
            CURLOPT_HTTPHEADER => self::$header
        ];
        if (!empty($postData) && is_array($postData)) {
            $jsonString = json_encode($postData);
            $curl = $curl->setOption(CURLOPT_POSTFIELDS, $jsonString);
        }
        if (!empty($options) && is_array($options)) {
            foreach ($options as $key1 => $value1) {
                $curl = $curl->setOption($key1, $value1);
            }
        }

        $url = \Yii::$app->params['zabbixapiurl'];
        $response = $curl->post($url);
        switch ($curl->responseCode) {

            case 'timeout':
                return ['status' => FALSE, 'error' => 'timeout'];
                break;

            case 200:
                return ['status' => TRUE, 'info' => json_decode($response, FALSE)];
                break;

            case 404:
                return ['status' => FALSE, 'error' => '404'];
                break;
        }
    }

}

?>