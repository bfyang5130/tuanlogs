<?php
/* @var $this yii\web\View */

use backend\models\SqlLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use backend\services\SqlTraceService ;

$this->title = '日志列表';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
$searchModel = new SqlLogSearch();
$dataProvider = $searchModel->search($params);
$dbtypes = SqlTraceService::getSqlTraceDbType() ;
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
                            'label' => '语句',
                            'filter' => Html::activeTextInput($searchModel, 'sqltext', ['class' => 'form-control']),
                            'format' => 'raw',
                            'value' => function($model) {
                                return '<div class="well">' . Html::encode($model->sqltext) . '</div>';
                            },
                        ],
                        [
                            'attribute' => 'sqlusedtime',
                            'label' => '耗时(ms)',
                            'headerOptions' => ['style' => 'width:80px;'],
                            'filter' => Html::activeTextInput($searchModel, 'sqlusedtime',['class' => 'form-control']),
                            'value' =>
                            function($model) {
                                return Html::encode($model->sqlusedtime);
                            },
                        ],
                                    [
                            'attribute' => 'databasetype',
                            'label' => '数据库',
                            'headerOptions' => ['style' => 'width:120px;'],
                            'filter' => Html::activeDropDownList($searchModel, 'databasetype',$dbtypes,['class' => 'form-control']),
                            'value' =>
                            function($model) {
                                return ($model->databasetype)?Html::encode($model->databasetype):'无记录';
                            },
                        ],
                        [
                            'attribute' => 'start_date',
                            'label' => '开始时间',
                            'value' => 'executedate',
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
                            'value' => 'executedate',
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
    });
</script>