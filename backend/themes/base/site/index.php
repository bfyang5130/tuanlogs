<?php
/* @var $this yii\web\View */

use backend\models\ErrorLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

$this->title = '日志列表';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
$searchModel = new ErrorLogSearch();
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
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group pull-right" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute('/site/index') ?>" class="btn btn-default">列表</a>
                        <a href="<?= Url::toRoute('/site/errorgraph') ?>" class="btn btn-default">图形</a>
                    </div>
                </div>
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
                            'headerOptions' =>['style'=>'width:80px;'],
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
                                return '<div class="well">'.Html::encode($model->Method).'</div>';
                            },
                        ],
                        [
                            'label' => '参数',
                            'headerOptions' =>['style'=>'width:45%;'],
                            'filter' => Html::activeTextInput($searchModel, 'Parameter', ['class' => 'form-control']),
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<div class="well">'.Html::encode($model->Parameter).'</div>';
                            },
                        ],
                        [
                            'attribute' => 'start_date',
                            'label' => '开始时间',
                            'value' => 'AddDate',
                            'filter' => \yii\jui\DatePicker::widget([
                                'model' => $searchModel,
                                'options'=>['style'=>'width:80px;'],
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
                                'options'=>['style'=>'width:80px;'],
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
if (isset($params['ErrorLogSearch']['Parameter']) && !empty($params['ErrorLogSearch']['Parameter'])):
    ?>
            $("#errorlogsearch-parameter").val("<?= $params['ErrorLogSearch']['Parameter'] ?>");
    <?php
endif;
if (isset($params['ErrorLogSearch']['Method']) && !empty($params['ErrorLogSearch']['Method'])):
    ?>
            $("#errorlogsearch-method").val("<?= $params['ErrorLogSearch']['Method'] ?>");
    <?php
endif;
if (isset($params['ErrorLogSearch']['start_date']) && !empty($params['ErrorLogSearch']['start_date'])):
    ?>
            $("#errorlogsearch-start_date").val("<?= $params['ErrorLogSearch']['start_date'] ?>");
    <?php
endif;
if (isset($params['ErrorLogSearch']['end_date']) && !empty($params['ErrorLogSearch']['end_date'])):
    ?>
            $("#errorlogsearch-end_date").val("<?= $params['ErrorLogSearch']['end_date'] ?>");
    <?php
endif;
?>
    });
</script>