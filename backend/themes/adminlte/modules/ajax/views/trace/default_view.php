<?php

use backend\models\TraceLogSearch;
use backend\models\ErrorLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

#获得具体日志统计记录
$p_get = \Yii::$app->request->get();
$params = \Yii::$app->request->queryParams;
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?php
            $typename = \common\models\ApplicateName::find("appname=:id", [':id' => $p_get['id']])->one();
            ?>
            <?= $typename->newname ?>
        </h1>
        <?=
        Breadcrumbs::widget([
            'tag' => 'ol',
            'itemTemplate' => "<li>{link}</li>\n", // template for all links
            'links' => [
                [
                    'label' => '跟踪日志',
                    'url' => ['/ajax/trace/index']
                ],
                [
                    'label' => $typename->newname
                ]
            ]
        ]);
        ?>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Hover Data Table</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <?php
                        Pjax::begin(['id' => 'countries']);
                        $searchModel = new TraceLogSearch();
                        $dataProvider = $searchModel->search($params);
                        ?>

                        <?php
                        echo GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                [
                                    'attribute' => 'ApplicationName',
                                    'label' => '类型',
                                    'value' =>
                                    function($model) {
                                        return Html::encode($model->ApplicationId);
                                    },
                                ],
                                [
                                    'label' => '函数',
                                    'filter' => Html::activeTextInput($searchModel, 'Method', ['class' => 'form-control']),
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return Html::encode($model->Method);
                                    },
                                ],
                                [
                                    'label' => '参数',
                                    'filter' => Html::activeTextInput($searchModel, 'Parameter', ['class' => 'form-control']),
                                    'format' => 'raw',
                                    'value' => function($model) {
                                        return Html::encode($model->Parameter);
                                    },
                                ],
                                [
                                    'attribute' => 'start_date',
                                    'label' => '开始时间',
                                    'value' => 'AddDate',
                                    'filter' => \yii\jui\DatePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'start_date',
                                        'language' => 'zh-CN',
                                        'dateFormat' => 'yyyy-MM-dd'
                                    ]),
                                    'format' => 'html',
                                ],
                                [
                                    'attribute' => 'end_date',
                                    'label' => '结束时间',
                                    'value' => 'AddDate',
                                    'filter' => \yii\jui\DatePicker::widget([
                                        'model' => $searchModel,
                                        'attribute' => 'end_date',
                                        'language' => 'zh-CN',
                                        'dateFormat' => 'yyyy-MM-dd',
                                        'value' => date('Y-m-d'),
                                    ]),
                                    'format' => 'html',
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
                                                'class' => 'show_model',
                                            ];
                                            $url = 'javascript:showDetaildiv("text' . $model->Id . '");';
                                            return Html::textarea('text' . $model->Id, Html::encode($model->Content), ['style' => 'display:none;', 'id' => 'text' . $model->Id]) . Html::a('<button type="button" class="btn btn-sm btn-info">查看详情</button>', $url, $options);
                                        }]
                                ],
                            ],
                        ]);
                        Pjax::end();
                        ?>
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function() {


<?php
if (isset($params['TraceLogSearch']['Parameter']) && !empty($params['TraceLogSearch']['Parameter'])):
    ?>
            $("#tracelogsearch-parameter").val("<?= $params['TraceLogSearch']['Parameter'] ?>");
    <?php
endif;
if (isset($params['TraceLogSearch']['Method']) && !empty($params['TraceLogSearch']['Method'])):
    ?>
            $("#tracelogsearch-method").val("<?= $params['TraceLogSearch']['Method'] ?>");
    <?php
endif;
if (isset($params['TraceLogSearch']['start_date']) && !empty($params['TraceLogSearch']['start_date'])) {
    ?>
            $("#tracelogsearch-start_date").val("<?= $params['TraceLogSearch']['start_date'] ?>");
    <?php
}
if (isset($params['TraceLogSearch']['end_date']) && !empty($params['TraceLogSearch']['end_date'])) {
    ?>
            $("#tracelogsearch-end_date").val("<?= $params['TraceLogSearch']['end_date'] ?>");
    <?php
}
?>
    });
</script>