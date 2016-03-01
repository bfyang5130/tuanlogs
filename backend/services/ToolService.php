<?php

namespace backend\services;

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

    //每500000分发一个作业
    const DISTRIBUTE_NUM = 500000 ;
    //每次读日志的行数
    const READ_LINE = 1000 ;

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
    public static function parseNginxAccessLog($body,$cdn_format=false){
        $china_cache_rs = false ;
        if($cdn_format==true){
            //使用CDN格式
            preg_match_all('/(.*?)[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',$body,$mat);
        }else{
            //不使用CDN格式
            $china_cache_rs = preg_match('/(ChinaCache)/',$body) ;
            if($china_cache_rs==true){
                preg_match_all('/(.*?)[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',$body,$mat);
            }else{
                preg_match_all('/(.*?)[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',$body,$mat);
            }
        }

        return array('mat'=>$mat,'china_cache_rs'=>$china_cache_rs) ;
    }

    public static function parseIp($str){
        preg_match_all('/\d+\.\d+\.\d+\.\d+/',$str,$mat) ;
        return $mat ;
    }

    public static function parseRequestInfo($body){
        preg_match_all('/(.*?)\s(.*?)\s(.*)/',$body,$mat);
        return $mat ;
    }

    //解析iis-access日志
    public static function parseIisAccessLog($body){
        preg_match_all('/(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s(.*?)\s/',$body,$mat);
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

    /**
     * 返回文件从X行到Y行的内容(支持php5、php4)
     * @param string $filename 文件名
     * @param int $startLine 开始的行数
     * @param int $endLine 结束的行数
     * @return string
     */
    public static function getFileLines($filename, $startLine = 1, $endLine=50, $method='rb') {
        $content = array();
        $count = $endLine - $startLine;
        $fp = new \SplFileObject($filename, $method);
        $fp->seek($startLine-1);// 转到第N行, seek方法参数从0开始计数
        for($i = 0; $i <= $count; ++$i) {
            $content[]=$fp->current();// current()获取当前行内容
            $fp->next();// 下一行
        }
        return array_filter($content); // array_filter过滤：false,null,''
    }

    /**
     * 高效率计算文件行数
     */
    public static function count_line($file){
        $fp = fopen($file, "rb");
        $i = 0;
        while(!feof($fp)) {
            if($data = fread($fp, 1024*1024*2)){
                $num = substr_count($data, PHP_EOL);
                $i += $num;
            }
        }
        fclose($fp);
        return $i;
    }

    public static function parseNginxDateTime($date_time){
        preg_match_all('/(\d+)[\/](.*?)[\/](\d+)[\:](\d+)[\:](\d+)[\:](\d+)/',$date_time,$mat) ;

        $year =empty($mat[3][0])?0:$mat[3][0];
        $month =empty($mat[2][0])?0:$mat[2][0];
        self::changeMonth($month) ;
        $day =empty($mat[1][0])?0:$mat[1][0];

        $hour =empty($mat[4][0])?0:$mat[4][0];
        $min =empty($mat[5][0])?0:$mat[5][0];
        $second =empty($mat[6][0])?0:$mat[6][0];

        $date = mktime($hour,$min,$second,$month,$day,$year) ;
        $date = date("Y-m-d H:i:s",$date) ;
        return $date ;
    }

    public static function changeMonth(&$month){
        $month_arr = array(
            1  => "Jan",
            2  => "Feb",
            3  => "Mar",
            4  => "Apr",
            5  => "May",
            6  => "Jun",
            7  => "Jul",
            8  => "Aug",
            9  => "Sep",
            10 => "Oct",
            11 => "Nov",
            12 => "Dec"
        );

        if(in_array($month,$month_arr)){
            $month = array_search($month,$month_arr) ;
        }else{
            $month = 0 ;
        }
    }

    /**
     * 根据日志名判断是否使用cdn format
     */
    public static  function isCdn($name){
        $cdn_arr = [
            'app.tuandai.com',
            'www.tuandai.com',
            'hd.tuandai.com',
            'm.tuandai.com',
            'image.hao8dai.com'
        ] ;

        if(in_array($name,$cdn_arr)){
            return true ;
        }else{
            return false ;
        }
    }

    /**
     * 解析文件名
     * @param $file_name
     * @return mixed
     */
    public static function parseFileName($file_name){
        preg_match("/(.*?)\.log/",$file_name,$mat) ;
        if(!empty($mat[1])){
            preg_match("/(.*?)\.access/",$mat[1],$s_mat) ;
            if(!empty($s_mat[1])){
                $short_name = $s_mat[1] ;
            }else{
                $short_name = $mat[1] ;
            }
        }else{
            $short_name = $file_name ;
        }
        return $short_name ;
    }

}

?>