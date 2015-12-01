<?php

use backend\services\AppcationNameService;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = "日志系统";
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
                    'url' => ['/ajax/default/index'],
                    'class' => 'ajax-link',
                ]
            ]
        ]);
        ?>
    </section>

    <!-- Main content -->
    <section class="content">
        <?php
#获得日志统计记录
        $dataProvider = AppcationNameService::findApplicationName();
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php Pjax::begin(['id' => 'countries']); ?>
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'appname',
                                    'label' => '日志名称'
                                ],
                                [
                                    'attribute' => 'newname',
                                    'label' => '自定义名称'
                                ],
                                [
                                    'attribute' => 'logtotal',
                                    'label' => '日志总数'
                                ],
                                [
                                    'attribute' => 'logtype',
                                    'label' => '日志类型',
                                    'value' =>
                                    function($model) {
                                        return $model->logtype == 0 ? "Error" : "Trace";
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
                                            ];
                                            $midurl = $model->logtype == 1 ? 'trace' : 'error';
                                            $url = 'ajax/' . $midurl . '/view.html?id=' . $model->id;
                                            return Html::a('<button type="button" class="btn btn-sm btn-info">查看</button>', $url, $options);
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