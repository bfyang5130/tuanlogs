<?php

use backend\services\LogTypeService;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            日志总览
        </h1>
        <?=
        Breadcrumbs::widget([
            'tag' => 'ol',
            'itemTemplate' => "<li>{link}</li>\n", // template for all links
            'links' => [
                [
                    'label' => '导航面板',
                    'url' => ['/ajax/trace/index'],
                ]
            ]
        ]);
        ?>
    </section>
    <section class="content">
        <?php
#获得日志统计记录
        $dataProvider = LogTypeService::findCustomlogType();
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">跟踪日志</h3>
                        <div class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="<?= Url::toRoute('/ajax/customlog/add') ?>"><i class="fa fa-fw fa-plus"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php Pjax::begin(['id' => 'countries']); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'logtype_id',
                                    'label' => '日志类型'
                                ],
                                [
                                    'attribute' => 'call_methods',
                                    'label' => '调用函数'
                                ],
                                [
                                    'attribute' => 'call_parameter',
                                    'label' => '调用参数'
                                ],
                                [
                                    'attribute' => 'add_time',
                                    'label' => '添加时间',
                                    'format' => ['date', 'php:Y-m-d H:i:s'],
                                    'value' => 'add_time'
                                ],
                                [
                                    'attribute' => 'fit_time',
                                    'label' => '处理时间',
                                    'value' => function($model) {
                                        return $model->fit_time?date('Y-m-d H:i:s', $model->fit_time):'未处理';
                                    },
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}',
                                    'buttons' => [
                                        // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                                        'view' => function ($url, $model, $key) {
                                            $options = [
                                                'title' => Yii::t('yii', 'View'),
                                                'aria-label' => Yii::t('yii', 'View'),
                                                'class' => 'ajax-link',
                                            ];
                                            return Html::a('<button type="button" class="btn btn-sm btn-info">查看详情</button>', $url, $options);
                                        }]
                                ],
                            ],
                        ]);
                        ?>
                        <?php Pjax::end(); ?>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </div>
    </section><!-- /.content -->
</div>