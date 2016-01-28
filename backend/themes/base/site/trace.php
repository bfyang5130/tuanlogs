<?php
/* @var $this yii\web\View */

use backend\models\TraceLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

$this->title = '日志列表';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
$searchModel = new TraceLogSearch();
$dataProvider = $searchModel->search($params);
?>
<div class="site-index">
    <?php
    echo Breadcrumbs::widget([
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => [
            [
                'label' => '首页'
            ],
        ],
    ]);
    ?>

    <div class="body-content">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <?php
                Pjax::begin(['id' => 'countries']);
                ?>
                <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['style' => 'width:80px;'],
                        ],
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
                                'options' => ['style' => 'width:80px;'],
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
                                'options' => ['style' => 'width:80px;'],
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
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
<?php
if (isset($params['TraceLogSearch']['Parameter']) && !empty($params['TraceLogSearch']['Parameter'])):
    ?>
            $("#traceLogSearch-parameter").val("<?= $params['TraceLogSearch']['Parameter'] ?>");
    <?php
endif;
if (isset($params['TraceLogSearch']['Method']) && !empty($params['TraceLogSearch']['Method'])):
    ?>
            $("#traceLogSearch-method").val("<?= $params['TraceLogSearch']['Method'] ?>");
    <?php
endif;
if (isset($params['TraceLogSearch']['start_date']) && !empty($params['TraceLogSearch']['start_date'])):
    ?>
            $("#traceLogSearch-start_date").val("<?= $params['TraceLogSearch']['start_date'] ?>");
    <?php
endif;
if (isset($params['TraceLogSearch']['end_date']) && !empty($params['TraceLogSearch']['end_date'])):
    ?>
            $("#traceLogSearch-end_date").val("<?= $params['TraceLogSearch']['end_date'] ?>");
    <?php
endif;
?>
    });
</script>