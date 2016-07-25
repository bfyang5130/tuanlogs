<?php

namespace backend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Thrift\Transport\TSocket;
use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TBufferedTransport;

/**
 * Site controller
 */
class ThriftController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'trace', 'sql', 'sqlgraph', 'errorgraph', 'addtype',
                            'getdata', 'doing', 'countday', 'countmonth', 'tracereport', 'tracedayreport', 'tracemonreport',
                            'tip', 'api'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * php-hbase测试
     */
    public function actionIndex() {
        $hbasevisit = new \backend\models\forms\HbaseVisitForm();
        $fitForm = \Yii::$app->request->get();
        if (!isset($fitForm['HbaseVisitForm'])) {
            $hbasevisit->start_time = date("Y-m-d 00:00:00");
            $hbasevisit->end_time = date("Y-m-d 23:59:59");
        } else {
            //设置数据的默认日期
            //处理异常的日期
            $starttime = date("His", strtotime($fitForm['HbaseVisitForm']['start_time']));
            $endtime = date("His", strtotime($fitForm['HbaseVisitForm']['end_time']));
            if ($endtime == $starttime) {
                $fitForm['HbaseVisitForm']['end_time'] = date("Y-m-d 23:59:59", strtotime($fitForm['HbaseVisitForm']['start_time']));
            }
            $hbasevisit->load($fitForm);
        }
        return $this->render('index', ['hbasevisit' => $hbasevisit]);
    }

    public function actionIndex1() {

        require_once dirname(\Yii::$app->basePath) . '/Hbase/Hbase.php';
        require_once dirname(\Yii::$app->basePath) . '/Hbase/Types.php';
        //'/Hbase/THBaseService.php';
        /*         * *
          Thrift Test

         */
        $host = '192.168.186.128';
        $port = 9090;

        $socket = new TSocket($host, $port);
        $socket->setRecvTimeout(10 * 1000);
        $transport = new TBufferedTransport($socket);
        $protocol = new TBinaryProtocol($transport);

// Create a client
        $client = new \Hbase\HbaseClient($protocol);
        $transport->open();
        //获得数据表
        //$tables = $client->getTableNames();
        //创建一个表
        //定义列名
        $tableName = 'messages';
        //$aritcle = new \Hbase\ColumnDescriptor(array('name' => 'aritcle:'));
        //$author = new \Hbase\ColumnDescriptor(array('name' => 'author:'));
        //$columns = array($aritcle, $author);
        //try {
        //    $client->createTable($tableName, $columns);
        //} catch (\Hbase\AlreadyExists $ex) {
        //    echo '表已经存在，不能重复创建';
        //}
        //删除已经存在的表
        //向表内插入数据
        //for ($i = 0; $i < 10000; $i++) {
        //    $record = array(new \Hbase\Mutation(array('column' => 'aritcle:title', 'value' => $i)));
        //    $client->mutateRow($tableName, $i, $record,[]);
        //}
        //获得数据
        $arr = $client->get($tableName, 2, 'aritcle:title', []);
// $arr = array  
        foreach ($arr as $k => $v) {
// $k = TCell  
            echo ("value = {$v->value} , <br>  ");
            echo ("timestamp = {$v->timestamp}  <br>");
        }
        $transport->close();
        exit;
//HOW TO GET
        $tableName = "test_table";

        $column_1 = new \TColumn();
        $column_1->family = 'cf1';
        $column_1->qualifier = 'q1';

        $column_2 = new \TColumn();
        $column_2->family = 'cf1';
        $column_2->qualifier = 'q2';

        $columnArray = array($column_1, $column_2);

        $get = new \TGet();
        $get->row = 'a';
        $get->columns = $columnArray;
        if ($client->exists($tableName, $get)) {
            echo 'Yes';
        } else {
            echo 'No';
        }
        exit;
        print_r($tables);
        exit;
        $arr = $client->get($tableName, $get);

        $results = $arr->columnValues;
        foreach ($results as $result) {
            $qualifier = (string) $result->qualifier;
            $value = $result->value;
            print_r($qualifier);
            print_r($value);
        }

//HOW TO SCAN
        $scan = new \TScan();
        $scan->startRow = 'a';
        $scan->stopRow = 'z';
        $scan->columns = $columnArray;
        $num = 1000;
        $scanRets = $client->getScannerRows($scanId, $num);

        foreach ($scanRets as $scanRet) {
            $scan_row = $scanRet->row;
            $scan_cols = $scanRet->columnValues;
            print_r($scan_row);
            print_r($scan_cols);
        }

        $client->closeScanner($scanId);
        $transport->close();




        echo 11;
        exit;
    }

}
