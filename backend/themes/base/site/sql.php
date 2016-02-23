<?php
/* @var $this yii\web\View */

use backend\models\SqlLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use backend\services\SqlTraceService;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = '日志列表';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
$searchModel = new SqlLogSearch();
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
            <?php
            $form = ActiveForm::begin([
                        'action' => ['/site/sql'],
                        'method' => 'get',
                        'options' => ['class' => 'form-inline','style'=>'margin:5px;padding:10px;'],
            ]);
            ?>
            <div class="col-lg-12">
                <?= $form->field($searchModel, 'sqltext', [ 'labelOptions' => ['label' => '语句:'], 'inputOptions' => ['class' => 'form-control','style'=>'width:450px;']]) ?>
                <?= $form->field($searchModel, 'databasetype', [ 'labelOptions' => ['label' => '数据库：']])->dropDownList(SqlTraceService::getSqlTraceDbType()); ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($searchModel, 'time_start', [ 'labelOptions' => ['label' => '耗时:'], 'inputOptions' => ['class' => 'form-control']]) ?>
                <label for="exampleInputEmail2">至</label>
                <?= $form->field($searchModel, 'time_end', [ 'labelOptions' => ['label' => ''], 'inputOptions' => ['class' => 'form-control']]) ?>
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
                </div>
                <div class="form-group">
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
                <button type="submit" class="btn btn-default btn-primary btn-sm">查询</button>
            </div>
                <?php ActiveForm::end(); ?>
            <div class="panel-body">
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
                            'label' => '语句',
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<div class="well">' . Html::encode($model->sqltext) . '</div>';
                            },
                        ],
                        [
                            'label' => '耗时(ms)',
                            'headerOptions' => ['style' => 'width:80px;'],
                            'value' =>
                            function($model) {
                                return Html::encode($model->sqlusedtime);
                            },
                        ],
                        [
                            'label' => '开始时间',
                            'value' => 'begindate'
                        ],
                        [
                            'label' => '结束时间',
                            'value' => 'enddate'
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
    });
</script>