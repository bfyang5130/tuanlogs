<?php

namespace backend\controllers;

use backend\services\ErrorLogService;
use Yii;
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
        return $this->render('index');
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

        $countday_arr = ErrorLogService::countByDay($page) ;
        $before_arr = $countday_arr['before_count'] ;
        $cur_arr = $countday_arr['cur_count'] ;

        $before_categories = array_keys($before_arr) ;
        $before_data = array_values($before_arr) ;

        $cur_categories = array_keys($cur_arr) ;
        $cur_data = array_values($cur_arr) ;

        return $this->render('day_count',[
            "before_categories"  =>$before_categories,
            "before_data"        =>$before_data,
            "cur_categories"     =>$cur_categories,
            "cur_data"           =>$cur_data,
            "format_before_time" =>$countday_arr['format_before_time'],
            "format_cur_time"    =>$countday_arr['format_cur_time'],
            "pre_page"           =>$pre_page ,
            "next_page"          =>$next_page ,
        ]);
    }

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

        $countday_arr = ErrorLogService::countByMonth($page) ;
        $before_arr = $countday_arr['before_count'] ;
        $cur_arr = $countday_arr['cur_count'] ;

        $before_categories = array_keys($before_arr) ;
        $before_data = array_values($before_arr) ;

        $cur_categories = array_keys($cur_arr) ;
        $cur_data = array_values($cur_arr) ;

        return $this->render('month_count',[
            "before_categories"  =>$before_categories,
            "before_data"        =>$before_data,
            "cur_categories"     =>$cur_categories,
            "cur_data"           =>$cur_data,
            "format_before_time" =>$countday_arr['format_before_time'],
            "format_cur_time"    =>$countday_arr['format_cur_time'],
            "pre_page"           =>$pre_page ,
            "next_page"          =>$next_page ,
        ]);
    }

}