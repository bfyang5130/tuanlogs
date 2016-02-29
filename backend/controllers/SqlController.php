<?php

namespace backend\controllers;

use backend\services\ErrorLogService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;
use backend\services\ToolService;
use backend\services\SqlTraceService;
use backend\models\SqlLogSearch;
use yii\data\Sort;
use backend\models\forms\DataBaseTypeForm;

/**
 * Site controller
 */
class SqlController extends Controller {

    /**
     * 增加统计信息
     * @return type
     */
    public function actionAddstatistics() {
        return $this->render("doing");
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
        $databaseForm = new DataBaseTypeForm();
        if (empty($day_data)) {
            return $this->render('sqlgraph', [
                        'databaseForm' => $databaseForm,
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
                    'databaseForm' => $databaseForm,
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
        $locals = ToolService::getPagedRows($query, ['orderBy' => $sort->orders, 'pageSize' => 10]);
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
                        'actions' => ['index', 'sqlgraph', 'addstatistics'],
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

}