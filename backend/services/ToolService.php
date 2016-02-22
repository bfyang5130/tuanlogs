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

    //解析access日志
    public static function parseAccessLog($body){
        preg_match_all('/(.*?)-(.*?)-(.*?),(.*?)-{1,}.*?[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',$body,$mat);
        return $mat ;

    }

    public static function parseRequestInfo($body){
        preg_match_all('/(.*?)\s(.*?)\s(.*)/',$body,$mat);
        return $mat ;
    }

}

?>