<?php
/* @var $this yii\web\View */

use backend\models\SqlLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

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
                            'value' =>
                            function($model) {
                                return Html::encode($model->sqlusedtime);
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
<?php
if (isset($params['SqlLogSearch']['sqltext']) && !empty($params['SqlLogSearch']['sqltext'])):
    ?>
            $("#sqllogsearch-sqltext").val("<?= $params['SqlLogSearch']['sqltext'] ?>");
    <?php
endif;
if (isset($params['SqlLogSearch']['end_date']) && !empty($params['SqlLogSearch']['end_date'])):
    ?>
            $("#sqllogsearch-end_date").val("<?= $params['SqlLogSearch']['end_date'] ?>");
    <?php
endif;
if (isset($params['SqlLogSearch']['start_date']) && !empty($params['SqlLogSearch']['start_date'])):
    ?>
            $("#sqllogsearch-start_date").val("<?= $params['SqlLogSearch']['start_date'] ?>");
    <?php
endif;
?>
    });
</script>