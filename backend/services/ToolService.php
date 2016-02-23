<?php

namespace backend\services;

use common\models\ApplicateName;
use common\models\ErrorLog;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Description of ToolService
 *
 * @author Administrator
 */
class ToolService {

    public static function getPagedRows($query, $config = [])
    {
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count()
        ]);
        if (isset($config['pageSize']))
        {
            $pages->setPageSize($config['pageSize'], true);
        }

        $rows = $query->offset($pages->offset)->limit($pages->limit);
        if (isset($config['orderBy']))
        {
            $rows = $rows->orderBy($config['orderBy']);
        }
        $rows = $rows->all();

        $rowsLable = 'datas';
        $pagesLable = 'pager';

        if (isset($config['rows']))
        {
            $rowsLable = $config['rows'];
        }
        if (isset($config['pages']))
        {
            $pagesLable = $config['pages'];
        }

        $ret = [];
        $ret[$rowsLable] = $rows;
        $ret[$pagesLable] = $pages;

        return $ret;
    }

    // 二维数组按某个key排序
    public static function array_sort($arr,$keys,$type='asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k=>$v){
            $keysvalue[$k] = $v[$keys];
        }
        if($type == 'asc'){
            asort($keysvalue);
        }else{
            arsort($keysvalue);
        }
        reset($keysvalue);
        $new_key = 0 ;
        foreach ($keysvalue as $k=>$v){
            $new_array[$new_key] = $arr[$k];
            $new_key = $new_key + 1;
        }
        return $new_array;
    }

    //解析nginx-access日志
    public static function parseNginxAccessLog($body){
        preg_match_all('/(.*?)-(.*?)-(.*?),(.*?)-{1,}.*?[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',$body,$mat);
        return $mat ;

    }

    public static function parseRequestInfo($body){
        preg_match_all('/(.*?)\s(.*?)\s(.*)/',$body,$mat);
        return $mat ;
    }

    //解析iis-access日志
    public static function parseIisAccessLog($body){
        preg_match_all('/(.*?)-(.*?)-(.*?),(.*?)-{1,}.*?[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',$body,$mat);
        return $mat ;
    }

    //解析Iis注释行
    public static function parseIisNote($body){
        $preg_rs = preg_match('/^#+/',$body) ;
        return $preg_rs ;
    }

    /**
     * 计算传入月份的第一天与最后一天
     * @param null $timestr
     */
    public static function getMonthFirstAndLastInfo($timestr=null){
        if(empty($timestr)){
            $format_str_time = date('Y-m-01') ;
            $str_time = strtotime($format_str_time); //获取从1号0点开始的时间戳。
        }else{
            $format_str_time = date("Y-m-01",$timestr) ;
            $str_time = strtotime($format_str_time) ;
        }
        $end_time = strtotime('+1 month -1 day', $str_time); //获取这个月最后一天23点59分的时间戳
        return ["str_time"=>$str_time,"end_time"=>$end_time] ;
    }

}

?>