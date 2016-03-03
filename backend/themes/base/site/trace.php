<?php
/* @var $this yii\web\View */

use backend\models\TraceLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = '日志列表';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
//月首
$month_info['str_time'] = date('Y-m-01 00:00:00');
//月尾
$month_info['end_time'] = date('Y-m-d 23:59:59', strtotime($month_info['str_time'] . " +1 month -1 day"));

$searchModel = new TraceLogSearch();
$applicationName = new \common\models\ApplicateName();
$category = $applicationName->find()->select('appname')->where(['logtype' => 1])->asArray()->all();
$category = ArrayHelper::map($category, 'appname', 'appname');
$dataProvider = $searchModel->search($params);
$searchModel->start_date = empty($searchModel->start_date) ? $month_info['str_time'] : $searchModel->start_date;
$searchModel->end_date = empty($searchModel->end_date) ? $month_info['end_time'] : $searchModel->end_date;
?>
<div class="site-index">
    <?php
    echo Breadcrumbs::widget([
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => [
            [
                'label' => '跟踪日志列表'
            ],
        ],
    ]);
    ?>

    <div class="body-content">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin:10px 0px;">
                    <div class="btn-group pull-right" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute(['site/trace']) ?>" class="btn btn-default">列表</a>
                        <a href="<?= Url::toRoute(['site/tracereport']) ?>" class="btn btn-default">图形</a>
                    </div>
                </div>
                <?php
                $form = ActiveForm::begin([
                            'id' => 'searchBox',
                            'action' => ['site/trace'],
                            'method' => 'get',
                            'options' => [
                                'class' => 'form-inline ',
                                'style' => 'margin:20px 0'
                            ],
                        ])
                ?>
                <?= $form->field($searchModel, 'ApplicationId')->dropDownList($category, ['style' => 'width:100px'])->label('类型')->error(false) ?>
                <?= $form->field($searchModel, 'Method')->textInput()->label('函数')->error(false) ?>
                <?= $form->field($searchModel, 'Parameter')->textInput($category)->label('参数')->error(false) ?>
                <div class="form-group">
                    <label for="exampleInputEmail2">时间：</label>
                    <?=
                    DateTimePicker::widget([
                        'language' => 'zh-CN',
                        'model' => $searchModel,
                        'attribute' => 'start_date',
                        'pickButtonIcon' => 'glyphicon glyphicon-time',
                        'template' => '{input}{button}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd hh:ii:ss',
                            'todayBtn' => true,
                        ],
                    ]);
                    ?>
                    <label for="exampleInputEmail2">至</label>
                    <?=
                    DateTimePicker::widget([
                        'language' => 'zh-CN',
                        'model' => $searchModel,
                        'attribute' => 'end_date',
                        'pickButtonIcon' => 'glyphicon glyphicon-time',
                        'template' => '{input}{button}',
                        'clientOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd hh:ii:ss',
                            'todayBtn' => true,
                        ],
                    ]);
                    ?>
                </div>
                <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
                <?php ActiveForm::end() ?>

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
//                            'headerOptions' => ['style' => 'width:80px;'],
                        ],
                        [
                            'attribute' => 'ApplicationName',
                            'label' => '类型',
//                            'filter' => Html::activeDropDownList($searchModel,'ApplicationId',$category, ['class' => 'form-control','prompt' => '全部']),
                            'value' =>
                            function ($model) {
                                return Html::encode($model->ApplicationId);
                            },
                        ],
                        [
                            'label' => '函数',
//                            'filter' => Html::activeTextInput($searchModel, 'Method', ['class' => 'form-control']),
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::encode($model->Method);
                            },
                        ],
                        [
                            'label' => '参数',
//                            'filter' => Html::activeTextInput($searchModel, 'Parameter', ['class' => 'form-control']),
                            'format' => 'raw',
                            'value' => function ($model) {
                                return Html::encode($model->Parameter);
                            },
                        ],
                        [
//                            'attribute' => 'start_date',
                            'label' => '开始时间',
                            'value' => 'AddDate',
//                            'filter' => \yii\jui\DatePicker::widget([
//                                'model' => $searchModel,
//                                'options' => ['style' => 'width:80px;'],
//                                'attribute' => 'start_date',
//                                'language' => 'zh-CN',
//                                'dateFormat' => 'yyyy-MM-dd'
//                            ]),
                            'format' => 'html',
                        ],
                        [
//                            'attribute' => 'end_date',
                            'label' => '结束时间',
                            'value' => 'AddDate',
//                            'filter' => \yii\jui\DatePicker::widget([
//                                'model' => $searchModel,
//                                'options' => ['style' => 'width:80px;'],
//                                'attribute' => 'end_date',
//                                'language' => 'zh-CN',
//                                'dateFormat' => 'yyyy-MM-dd',
//                                'value' => date('Y-m-d'),
//                            ]),
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

