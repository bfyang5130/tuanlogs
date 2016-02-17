<?php

namespace backend\controllers;

use backend\models\ErrorLogSearch;
use backend\services\ErrorLogService;
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
                        'actions' => ['logout', 'index', 'trace', 'sql', 'errorgraph', 'getdata','doing','countday','countmonth'],
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
        $pie_data = array() ;
        foreach($application_list as $application){
            $pie_data[] = [$application['ApplicationId'],floatval($application['total'])] ;
        }
        return $this->render('errorgraph',['pie_data'=>$pie_data]);
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
        return $this->render('index',$locals);
    }

    public function actionTrace() {
        return $this->render('trace');
    }

    /**
     * 数据库信息
     */
    public function actionSql() {
        return $this->render('sql');
    }

    public function actionLogin() {
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

        $day_data = ErrorLogService::countByDay($page) ;
        $appnames = $day_data["appnames"] ;
        $items = $day_data["items"] ;

        $series =array() ;
        $i = 0 ;
        foreach($items as $key=>$item){
            $series[$i]['name'] =$key ;
            $series[$i]['data']= array_values($item) ;
            $i = $i + 1 ;
        }

        return $this->render('day_count',[
            "appnames"   =>$appnames,
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

        $series =array() ;
        $i = 0 ;
        foreach($items as $key=>$item){
            $series[$i]['name'] =$key ;
            $series[$i]['data']= array_values($item) ;
            $i = $i + 1 ;
        }

        return $this->render('month_count',[
            "appnames"   =>$appnames,
            "series"     =>$series,
            "pre_page"   =>$pre_page ,
            "next_page"  =>$next_page ,
        ]);
    }

}