<?php

use backend\services\LogTypeService;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = "自定义日志类型";
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            类型列表
        </h1>
        <?=
        Breadcrumbs::widget([
            'tag' => 'ol',
            'itemTemplate' => "<li>{link}</li>\n", // template for all links
            'links' => [
                [
                    'label' => '导航面板',
                    'url' => ['/ajax/trace/index']
                ]
            ]
        ]);
        ?>
    </section>
    <section class="content">
        <?php
#获得日志统计记录
        $dataProvider = LogTypeService::findLogType();
        ?>
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">跟踪日志</h3>
                        <div class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="<?= Url::toRoute('/ajax/logtype/add') ?>"><i class="fa fa-fw fa-plus"></i></a></li>
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
                                    'attribute' => 'type_name',
                                    'label' => '英文名称'
                                ],
                                [
                                    'attribute' => 'type_cn_name',
                                    'label' => '中文名称'
                                ],
                                [
                                    'class' => 'yii\grid\ActionColumn',
                                    'template' => '{view}',
                                    'buttons' => [
                                        // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                                        'view' => function ($url, $model, $key) {
                                            $options = [
                                                'title' => Yii::t('yii', 'View'),
                                                'aria-label' => Yii::t('yii', 'View')
                                            ];
                                            $url = 'ajax/customlog/type.html?id=' . $model->id;
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