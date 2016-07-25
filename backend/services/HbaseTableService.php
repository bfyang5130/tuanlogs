<?php

namespace backend\services;

use Thrift\Transport\TSocket;
use Thrift\Transport\TBufferedTransport;
use Thrift\Protocol\TBinaryProtocol;

require_once dirname(\Yii::$app->basePath) . '/Hbase/Hbase.php';
require_once dirname(\Yii::$app->basePath) . '/Hbase/Types.php';

/**
 * Description of AccessLogService
 * Yii2对hbase进一步的处理
 * @author Administrator
 */
class HbaseTableService extends \Hbase\HbaseClient {

    const HOST = '192.168.186.128';
    const PORT = 9090;
    const TIMEOUT = 10000;

    public static $client = null;
    public static $transport = null;

    /**
     * 获得一个client
     */
    public static function getClient() {
        if (self::$client == NULL) {
            $socket = new TSocket(self::HOST, self::PORT);
            $socket->setRecvTimeout(10 * 1000);
            self::$transport = new TBufferedTransport($socket);
            $protocol = new TBinaryProtocol(self::$transport);
            self::$client = new \Hbase\HbaseClient($protocol);
        }
        return self::$client;
    }

    /**
     * 获得Hbase数据表的信息
     */
    public function findTablesLists() {
        $client = self::getClient();
        self::$transport->open();
        //获得数据表
        $tableList = $client->getTableNames();

        self::$transport->close();
        return $tableList;
    }

    /**
     * 获得指定范围条件查询数据
     */
    public static function findDataLists() {
        //获得查询的信息
        $tablename = '';
        $showCols = [];
        $searchFit = \Yii::$app->request->get();
        $filter = '';
        if (isset($searchFit['HbaseVisitForm'])) {
            //配置tablename
            $partone = $searchFit['HbaseVisitForm']['proxy'];
            $partTwo = Date("Ymd", strtotime($searchFit['HbaseVisitForm']['start_time']));
            $tablename = $partone . $partTwo;
            //处理要显示的信息
            foreach ($searchFit['HbaseVisitForm']['show_check'] as $oneSelect) {
                $showCols[] = 'info:' . $oneSelect;
            }
            $keyword = trim($searchFit['HbaseVisitForm']['key_word']);

            if (!empty($keyword)) {
                if($searchFit['HbaseVisitForm']['search_col']='ip'){
                    $keyword=  str_replace(".", "\\.", $keyword);
                }
                $filter = "SingleColumnValueFilter('info','{$searchFit['HbaseVisitForm']['search_col']}',=,'regexstring:.*125\\.84\\.84\\.187.*')";
            }
        } else {
            $searchFit['HbaseVisitForm'] = [];
            //默认显示当天nginx日志的内容
            //开始配置查询内容
            //$tablename='nginx'.date('Ymd');
            $tablename = 'nginx20160713';
            //默认显示数据
            //增加查询条件

            $showCols = ['info:country', 'info:province', 'info:city', 'info:ip', 'info:request_url', 'info:datereg'];
        }
        //获得查询的KEY值范围
        List($page, $mdKey, $startKey, $endKey) = self::returnSearchKey($tablename, $searchFit);
        $client = self::getClient();
        self::$transport->open();
        //创建一个查询模型
        $scan = new \Hbase\TScan();
        $scan->startRow = $startKey;
        $scan->stopRow = $endKey;
        $scan->columns = $showCols;
        $scan->caching=1000;
        if (!empty($filter)) {
            $scan->filterString = $filter;
        }
        //$scan->filterString="RowFilter(=, 'regexstring:0001000002000018')";
        //$scan->filterString="RowFilter(=, '0001000002000018')";
        //$scan->filterString = "SingleColumnValueFilter('info','country',=,'binary:中国')";
        $scanner = $client->scannerOpenWithScan("nginx20160713", $scan, []);
        $get_arr = $client->scannerGetList($scanner, 20);
        self::$transport->close();
        if (!$get_arr) {
            return [];
        }
        //处理th描述
        $chineseWord = \backend\models\forms\HbaseVisitForm::showlist();
        $thData = [];
        //循环处理第一个数组的数据,得到TH内容

        foreach ($get_arr[0]->columns as $key => $indexOneDate) {
            $newKey1 = explode(':', $key);
            $newKey = $newKey1[1];
            $thData[] = $chineseWord[$newKey];
        }
        //存储分页
        $dateEndnum = count($get_arr);
        $filePage[$page]['start'] = $get_arr[0]->row;
        $filePage[$page]['end'] = $get_arr[$dateEndnum - 1]->row;
        \Yii::$app->cache->set($mdKey, $dateEndnum);
        //获得查询的串生成MDKEY
        return [
            'th' => $thData,
            'data' => $get_arr,
            'page' => $page,
        ];
    }

    /**
     * 获得需要查询的开始，结束KEY
     */
    public static function returnSearchKey($tablename, $searchFit) {
        //得到默认的范围KEY值
        $startRow = '0001000000000000';
        $endRow = '0001235959999999';
        //如果存在查询数据
        if (!empty($searchFit['HbaseVisitForm'])) {
            //得到表
            $partone = $searchFit['HbaseVisitForm']['web_visit'];
            $partTwo = Date("His", strtotime($searchFit['HbaseVisitForm']['start_time']));
            $startRow = $partone . $partTwo . '000000';
            $partTwos = Date("His", strtotime($searchFit['HbaseVisitForm']['end_time']));
            $endRow = $partone . $partTwos . '999999';
        }
        //得到KEY值
        //生成序列化
        $keyseriKey = serialize($searchFit);
        //生成唯一MD5key值
        $key = md5($tablename . $keyseriKey);
        //查询是否当前的查询是否已经记录在已有的KEY值内
        $filePage = \Yii::$app->cache->get($key);
        //通过分页分析是否
        $page = 1;
        if (isset($searchFit['page'])) {
            $page = $searchFit['page'];
        }
        //如果已经有当前页的记录，就返回记录，否则得到最小的的记录
        if ($filePage && isset($filePage[$page])) {
            $startRow = $filePage[$page]['start'];
            $endRow = $filePage[$page]['end'];
        } elseif ($filePage) {
            $maxnum = count($filePage);
            $startRow = $filePage[$maxnum - 1]['start'];
        }
        return [$page, $key, $startRow, $endRow];
    }

}

?>