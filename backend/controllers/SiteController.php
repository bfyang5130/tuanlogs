<?php

namespace backend\controllers;

use backend\models\ErrorLogSearch;
use backend\models\SqlLogSearch;
use backend\services\ErrorLogService;
use backend\services\TraceLogService;
use backend\services\ToolService;
use Yii;
use yii\data\Sort;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use common\models\LoginForm;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller {

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
                        'actions' => ['logout', 'index', 'trace', 'sql', 'errorgraph',
                            'getdata','doing','countday','countmonth','tracereport','tracedayreport','tracemonreport'],
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
     * 内容建设中
     * @return type
     */
    public function actionDoing(){
        return $this->render("doing");
    }

    public function actionGetdata() {

    }

    /**
     * 错误的图标显示
     */
    public function actionErrorgraph() {
        $application_list = ErrorLogService::countErrorByApplicationId() ;
        $appnames = array() ;
        $data =array() ;
        $sort_application_list = ToolService::array_sort($application_list,"total","desc");
        foreach($sort_application_list as $application){
            $appnames[] = $application['ApplicationId'] ;
            $data[]   = floatval($application['total']) ;
        }
        $series['name']="错误日志" ;
        $series['data']=$data ;
        return $this->render('errorgraph',['appnames'=>$appnames,'series'=>array($series)]);
    }

    public function actionIndex() {
        $params = Yii::$app->request->get();
        $searchModel = new ErrorLogSearch();
        $dataProvider = $searchModel->search($params);
        $query = $dataProvider->query;
        $sort = new Sort([
            'attributes' => [
                'AddDate',
            ],
            'defaultOrder'=>['AddDate'=>SORT_DESC]
        ]);
        $locals = ToolService::getPagedRows($query,['orderBy'=>$sort->orders,'pageSize'=>10]);
        $locals['searchModel']=$searchModel;

        $application_item = ErrorLogService::getApplicationNameItem(1) ;
        $locals['application_item']=$application_item;
        return $this->render('index',$locals);
    }

    public function actionTrace() {
        return $this->render('trace');
    }

    /**
     * 数据库信息
     */
    public function actionSql() {
        $params = Yii::$app->request->get();
        $searchModel = new SqlLogSearch();
        $dataProvider = $searchModel->search($params);
        $query = $dataProvider->query;
        $sort = new Sort([
                'attributes' => [
                        'executedate',
                ],
                'defaultOrder'=>['executedate'=>SORT_DESC]
        ]);
        $locals = ToolService::getPagedRows($query,['orderBy'=>$sort->orders,'pageSize'=>10]);
        $locals['searchModel']=$searchModel;
        return $this->render('sql',$locals);
    }

    public function actionLogin() {
        $this->layout='login';
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * 日统计ErrorLog
     * @return string
     */
    public function actionCountday(){
        $page = Yii::$app->request->get("page") ;
        if(empty($page)){
            $page = 0 ;
        }
        if(!empty($page) && $page>0){
            $page = 0 ;
        }
        $pre_page = $page - 1 ;
        $next_page = $page + 1 ;
        if($next_page>0){
            $next_page = 0 ;
        }

        $search_date = Yii::$app->request->get("search_date") ;
        $day_data = ErrorLogService::countByDay($page,$search_date) ;
        $appnames = $day_data["appnames"] ;
        $items = $day_data["items"] ;

        //统计各个分类总数
        $sort_items = array() ;
        foreach($appnames as $a_val){
            foreach($items as $key=>$item){
                foreach($item as $i_key=>$i_val){
                    if($i_key==$a_val){
                        $sort_items[$a_val] = empty($sort_items[$a_val])?0:$sort_items[$a_val] ;
                        $sort_items[$a_val] = $sort_items[$a_val] + floatval($i_val) ;
                    }
                }
            }
        }

        //降序排序
        arsort($sort_items) ;

        //取排序后的分类字段
        $sort_appnames = array_keys($sort_items) ;

        //按排完序的重新给值
        foreach($items as $key=>$item){
            foreach($sort_appnames as $t_appname){
                $arr_item[$t_appname] = empty($item[$t_appname])?0:$item[$t_appname] ;
            }
            $items[$key] = $arr_item ;
        }

        $series =array() ;
        $i = 0 ;
        foreach($items as $key=>$item){
            $series[$i]['name'] =$key ;
            $series[$i]['data']= array_values($item) ;
            $i = $i + 1 ;
        }

        return $this->render('day_count',[
            "appnames"   =>$sort_appnames,
            "series"     =>$series,
            "pre_page"   =>$pre_page ,
            "next_page"  =>$next_page ,
        ]);
    }

    /**
     * 月统计ErrorLog
     * @return string
     */
    public function actionCountmonth(){
        $page = Yii::$app->request->get("page") ;
        if(empty($page)){
            $page = 0 ;
        }
        if(!empty($page) && $page>0){
            $page = 0 ;
        }
        $pre_page = $page - 1 ;
        $next_page = $page + 1 ;
        if($next_page>0){
            $next_page = 0 ;
        }

        $month_data = ErrorLogService::countByMonth($page) ;
        $appnames = $month_data["appnames"] ;
        $items = $month_data["items"] ;

        //统计各个分类总数
        $sort_items = array() ;
        foreach($appnames as $a_val){
            foreach($items as $key=>$item){
                foreach($item as $i_key=>$i_val){
                    if($i_key==$a_val){
                        $sort_items[$a_val] = empty($sort_items[$a_val])?0:$sort_items[$a_val] ;
                        $sort_items[$a_val] = $sort_items[$a_val] + floatval($i_val) ;
                    }
                }
            }
        }

        //降序排序
        arsort($sort_items) ;

        //取排序后的分类字段
        $sort_appnames = array_keys($sort_items) ;

        //按排完序的重新给值
        foreach($items as $key=>$item){
            foreach($sort_appnames as $t_appname){
                $arr_item[$t_appname] = empty($item[$t_appname])?0:$item[$t_appname] ;
            }
            $items[$key] = $arr_item ;
        }

        $series =array() ;
        $i = 0 ;

        foreach($items as $key=>$item){
            $series[$i]['name'] =$key ;
            $series[$i]['data']= array_values($item) ;
            $i = $i + 1 ;
        }

        $years = ErrorLogService::getYearList() ;

        return $this->render('month_count',[
            "appnames"   =>$sort_appnames,
            "series"     =>$series,
            "pre_page"   =>$pre_page ,
            "next_page"  =>$next_page ,
            "years"      =>$years
        ]);
    }


    public function actionTracereport()
    {
        $traceService = new TraceLogService();
        $data = [];
        foreach($traceService->TraceGroupBy() as $trace){
            $data[] = [$trace['ApplicationId'],floatval($trace['total'])];
        }
        $data = TraceLogService::getTraceCategory();
        $data['type'] = '';
        return $this->render('tracereport',$data);
    }

    public function actionTracedayreport()
    {
        return $this->render('tracereport', TraceLogService::CountDay());
    }

    public function actionTracemonreport()
    {
        $dateline = Yii::$app->request->get('years',null);
        $data = TraceLogService::CountMon($dateline);
        return $this->render('tracereport',$data);
    }
}