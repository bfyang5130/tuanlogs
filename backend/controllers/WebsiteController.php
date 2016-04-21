<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\TargetSourceUrl;
use backend\services\ToolService;

/**
 * Site controller
 */
class WebsiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => [ 'index', 'product'],
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
     * 显示nginx访问首页
     * @return type
     */
    public function actionIndex() {
        return $this->render('index');
    }

    public function actionProduct() {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $id = \Yii::$app->request->get('id');
        $sDate = date('Y-m-d 00:00:00');
        $eDate = date('Y-m-d 00:00:00', strtotime("+1 day", strtotime($sDate)));

        if ($id) {
            //获得指定的数据
            $oneUrl = TargetSourceUrl::find()->where('id=:id', [':id' => $id])->one();
            //上一级的访问图
            $prelistss = TargetSourceUrl::find()->select("id,from_url,sum(nums) nums")->where('target_url=:target_url', [':target_url' => $oneUrl->target_url])->andWhere('vist_time>=:sd AND vist_time<:ed', [':sd' => $sDate, ':ed' => $eDate])->asArray()->groupBy("from_url")->all();
            //处理一个公约数实现能量平衡
            $prelistsscountnums = 0;
            if ($prelistss) {
                foreach ($prelistss as $key => $value) {
                    $prelistsscountnums = $prelistsscountnums + $value['nums'];
                }
            }else{
                $prelistsscountnums=1;
            }
            $listsscountnums = 0;
            //下一级访问图
            $listss = TargetSourceUrl::find()->select("id,target_url,sum(nums) nums")->where('from_url=:from_url', [':from_url' => $oneUrl->target_url])->andWhere('vist_time>=:sd AND vist_time<:ed', [':sd' => $sDate, ':ed' => $eDate])->asArray()->groupBy("target_url")->all();
            if ($listss) {
                foreach ($listss as $key => $value) {
                    $listsscountnums = $listsscountnums + $value['nums'];
                }
            }else{
                $listsscountnums=1;
            }
            $beiNums = ToolService::min_multiple($prelistsscountnums, $listsscountnums);
            //获得上一级数据的倍数
            $prbei = $beiNums / $prelistsscountnums;
            //获得下一级数据的倍数
            $bei = $beiNums / $listsscountnums;
            //处理上一级的数据
            if (!$prelistss) {
                $data['nodes'][] = ['name' => $oneUrl->target_url];
                $data['nodes'][] = ['name' => '[0][F]起点-无数据'];
                $data['links'][] = ["source" => '[0][F]起点-无数据', "target" => $oneUrl->target_url, "value" => 1 * $prbei];
            } else {
                $data['nodes'][] = ['name' => $oneUrl->target_url];
                foreach ($prelistss as $key => $value) {
                    $name = "[" . $value['id'] . "][F]" . $value['from_url'];
                    $data['nodes'][] = ['name' => $name];
                    $data['links'][] = ["source" => $name, "target" => $oneUrl->target_url, "value" => $value['nums'] * $prbei];
                }
            }
            if (!$listss) {
                $data['nodes'][] = ['name' => '终点-点击返回首页数据[T][0]'];
                $data['links'][] = ["source" => $oneUrl->target_url, "target" => '终点-点击返回首页数据[T][0]', "value" => 1 * $bei];
            } else {
                foreach ($listss as $key => $value) {
                    $name = $value['target_url'] . "[T][" . $value['id'] . "]";
                    $data['nodes'][] = ['name' => $name];
                    $data['links'][] = ["source" => $oneUrl->target_url, "target" => $name, "value" => $value['nums'] * $bei];
                }
            }
        } else {
            //获得首页开始的流动数据
            $listss = TargetSourceUrl::find()->select("id,target_url,sum(nums) nums")->where('from_url="-"')->andWhere('vist_time>=:sd AND vist_time<:ed', [':sd' => $sDate, ':ed' => $eDate])->asArray()->groupBy("target_url")->all();
            $data['nodes'][] = ['name' => '首页'];
            foreach ($listss as $key => $value) {
                if ($value['target_url'] == 'http://www.tuandai.com/view/ip.aspx' || $value['target_url'] == 'http://www.tuandai.com/MobileAPI/GetConnectionType.aspx') {
                    continue;
                }
                $name = $value['target_url'] . "[" . $value['id'] . "]";
                $data['nodes'][] = ['name' => $name];
                $data['links'][] = ["source" => "首页", "target" => $name, "value" => $value['nums']];
            }
        }
        return $data;
    }

}