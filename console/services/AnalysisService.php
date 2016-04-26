<?php

namespace console\services;

use backend\services\ToolService;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class AnalysisService {

    /**
     * 分析用户的访问流并进行相应的记录
     * @param type $fitDataArray
     */
    public static function userVisitFlow($fitDataArray, $li_short_name) {
        //获得解释过后的一个数组
        $mat = $fitDataArray['mat'];
        //获得请求页面
        if (!isset($mat[3][0])) {
            return false;
        }
        $accPage = $mat[3][0];
        $newInfo = ToolService::parseRequestInfo($accPage);
        //获得正常的表达式
        if(!isset($newInfo[2][0])){
            return FALSE;
        }
        $realUrl = self::trimURl($newInfo[2][0], $li_short_name);
        //如果请求连接有问题那么直接返回错误
        if ($realUrl === FALSE) {
            return false;
        }
        //获得来源页面
        if (!isset($mat[6][0])) {
            return FALSE;
        }
        $sourcePage = $mat[6][0];
        $fromUrl = self::trimFromURL($sourcePage);
        //保存数据到文件缓冲
        $target = \Yii::$app->cache->get('target');
        if ($target !== false) {
            if (isset($target[$realUrl][$fromUrl])) {
                $target[$realUrl][$fromUrl] = $target[$realUrl][$fromUrl] + 1;
            } else {
                $target[$realUrl][$fromUrl] = 1;
            }
        } else {
            $target[$realUrl][$fromUrl] = 1;
        }
        //保存这个数据到缓冲文件,10分钟处理一次数据时，会把数据入库
        \Yii::$app->cache->set('target', $target);
    }

    /**
     * 处理来源页面
     * @param type $string
     * @return type
     */
    public static function trimFromURL($string) {
        if (strpos($string, '?')) {
            $preString = substr($string, 0, strpos($string, '?'));
        } else {
            $preString = $string;
        }
        return $preString;
    }

    /**
     * 处理请求的地址
     * @param type $string
     * @return type
     */
    public static function trimURL($string, $li_short_name) {
        //只处理.aspx页面
        $resutl = preg_match('/(\.aspx|\.ashx)/i', $string);
        if (!$resutl) {
            return false;
        }
        //去掉特殊的地址
        if(strpos($string,'view/ip.aspx')!==FALSE){
            return false;
        }
         //去掉特殊的地址
        if(strpos($string,'MobileAPI/GetConnectionType.aspx')!==FALSE){
            return false;
        }
        //截取ASPX ASHX之前的字符进行分析处理
        $preString = '';
        if (strpos($string, '.aspx')) {
            $preString = substr($string, 0, strpos($string, '.aspx') + 5);
        }
        if (strpos($string, '.ashx')) {
            $preString = substr($string, 0, strpos($string, '.ashx') + 5);
        }
        //如果是一些很奇怪的页面，那么不做处理
        if (strpos($preString, '%')) {
            return FALSE;
        }
        if (strpos($preString, '/') == 0) {
            $preString = $li_short_name . ":" . $preString;
        }
        return $preString;
    }

}

?>