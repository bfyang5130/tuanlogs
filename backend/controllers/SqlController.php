<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use backend\services\ToolService;
use backend\services\SqlTraceService;
use backend\models\SqlLogSearch;
use yii\data\Sort;
use backend\models\forms\DataBaseTypeForm;
use backend\models\forms\TableFitForm;
use common\models\DatabaseType;

/**
 * Site controller
 */
class SqlController extends Controller {

    public function actionSqlnums(){
        return $this->render("sqlnums");
    }
    /**
     * 查询某个数据库的统计
     * @return type
     */
    public function actionDatabase() {
        $gets = \Yii::$app->request->get();
        #获得选择的数据库
        if (isset($gets['type'])) {
            $selectDatabase = \common\models\DatabaseType::findOne($gets['type']);
            if ($selectDatabase) {
                return $this->render('database');
            }
        }
        return $this->redirect('/sql/sqlgraph.html');
    }

    /**
     * 增加统计信息
     * @return type
     */
    public function actionAddstatistics() {
        $databaseForm = new DataBaseTypeForm();
        $newTableFitForm = new TableFitForm();
        $fitForm = Yii::$app->request->post();
        $databaseFit = 0;
        $tableFit = 0;
        if (isset($fitForm['DataBaseTypeForm'])) {
            $databaseForm->load(Yii::$app->request->post());

            if ($databaseForm->save()) {
                $databaseFit = 1;
            } else {
                $databaseFit = 2;
            }
        }
        if (isset($fitForm['TableFitForm'])) {
            $newTableFitForm->load(Yii::$app->request->post());

            if ($newTableFitForm->save()) {
                $tableFit = 1;
            } else {
                $tableFit = 2;
            }
        }
        return $this->render('addstatistics', [
                    'databaseForm' => $databaseForm,
                    'tableFitForm' => $newTableFitForm,
                    'databaseFit' => $databaseFit,
                    'tableFit' => $tableFit
                        ]
        );
    }

    /**
     * 数据库相关的统计
     */
    public function actionSqlgraph() {
        $page = Yii::$app->request->get("page");
        if (empty($page)) {
            $page = 0;
        }
        $pre_page = $page - 1;
        $next_page = $page + 1;

        $search_date = Yii::$app->request->get("search_date");
        if (empty($search_date)) {
            $search_date = date("Y-m-d");
        }
        $day_data = SqlTraceService::getSqlDayGraph($page, $search_date);
        if (empty($day_data)) {
            return $this->render('sqlgraph', [
                        'search_date' => $search_date,
                        "pre_page" => $pre_page,
                        "next_page" => $next_page
            ]);
        }
        $appnames = $day_data["appnames"];
        $series['name'] = $day_data["search_date"] . "访问统计";
        $series['data'] = $day_data['data']["totalVisit"];
        $series['dataLabels']['enabled'] = true;
        $series1['name'] = $day_data["search_date"] . "每秒访问频率";
        $series1['data'] = $day_data['data']["totalsecondVisit"];
        $series1['dataLabels']['enabled'] = true;
        $appnameshourshow = $day_data['data']["hourshow"];
        $series2 = $day_data['data']["reline24Visit"];
        $series3 = $day_data['data']["reline24VisitSc"];
        $series4 = $day_data['data']["reline24Time"];
        $series5 = $day_data['data']["reline24Timesec"];

        return $this->render('sqlgraph', [
                    'search_date' => $search_date,
                    "appnames" => $appnames,
                    "appnameshourshow" => $appnameshourshow,
                    "series" => array($series),
                    "series1" => array($series1),
                    "series2" => $series2,
                    "series3" => $series3,
                    "series4" => $series4,
                    "series5" => $series5,
                    "pre_page" => $pre_page,
                    "next_page" => $next_page
        ]);
    }

    /**
     * 慢日志查询
     */
    public function actionLongtimesql() {
        return $this->render('longtimesql');
    }
    /**
     * 50慢日志查询
     */
    public function actionSql50() {
        return $this->render('sql50');
    }
    /**
     * 首页信息
     * @return type
     */
    public function actionIndex() {
        $params = Yii::$app->request->get();
        $searchModel = new SqlLogSearch();
        $dataProvider = $searchModel->search($params);
        $query = $dataProvider->query;
        $sort = new Sort([
            'attributes' => [
                'executedate',
            ],
            'defaultOrder' => ['executedate' => SORT_DESC]
        ]);
        $locals = ToolService::getPagedRows($query,$tablename='SqlTrace',$params, ['orderBy' => $sort->orders, 'pageSize' => 10]);
        $locals['searchModel'] = $searchModel;
        return $this->render('sql', $locals);
    }

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
                        'actions' => ['index', 'sqlgraph', 'addstatistics', 'database', 'longtimesql','sql50','sqlnums'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                //'addstatistics'=>['post'],
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

}