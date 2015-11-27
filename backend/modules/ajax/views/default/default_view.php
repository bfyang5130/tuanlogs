<?php

use backend\models\TraceLogSearch;
use backend\models\ErrorLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
            <?=
            Breadcrumbs::widget([
                'itemTemplate' => "<li>{link}</li>\n", // template for all links
                'links' => [
                    [
                        'label' => '错误信息',
                        'url' => ['/ajax/default/index'],
                        'class' => 'ajax-link',
                    ]
                ]
            ]);
            ?>
        </ol>
        <div id="social" class="pull-right">
            <?php /**
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
             * 
             */ ?>
        </div>
    </div>
</div>
<?php
#获得具体日志统计记录
$p_get = \Yii::$app->request->get();
$params = \Yii::$app->request->queryParams;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-table"></i>
                    <span>日志总览</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <?php
                Pjax::begin(['id' => 'countries']);
                if (isset($p_get['type']) && $p_get['type'] != 1) {
                    $searchModel = new ErrorLogSearch();
                    $dataProvider = $searchModel->search($params);
                    ?>
                    <script type="text/javascript">
                        $(document).ready(function() {
    <?php
    if (isset($params['ErrorLogSearch']['start_date']) && !empty($params['ErrorLogSearch']['start_date'])) {
        ?>
                                $("#ErrorLogSearch-start_date").val("<?= $params['ErrorLogSearch']['start_date'] ?>");
        <?php
    }
    if (isset($params['ErrorLogSearch']['end_date']) && !empty($params['ErrorLogSearch']['end_date'])) {
        ?>
                                $("#ErrorLogSearch-end_date").val("<?= $params['ErrorLogSearch']['end_date'] ?>");
        <?php
    }
    ?>
                        });
                    </script>
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
                                    return Html::encode($model->ApplicationName);
                                },
                            ],
                            [
                                'attribute' => 'Method',
                                'label' => '函数',
                                'value' =>
                                function($model) {
                                    return Html::encode($model->Method);
                                },
                            ],
                            [
                                'attribute' => 'Parameter',
                                'label' => '参数',
                                'value' =>
                                function($model) {
                                    return Html::encode($model->Parameter);
                                },
                            ],
                            [
                                'attribute' => 'Content',
                                'label' => '详情',
                                'headerOptions' => ['class' => 'maxwidth'],
                                'value' =>
                                function($model) {
                                    return Html::encode($model->Content);
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
                        ],
                    ]);
                } else {
                    $searchModel = new TraceLogSearch();
                    $dataProvider = $searchModel->search($params);
                    ?>
                    <script type="text/javascript">
                        $(document).ready(function() {
    <?php
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
                                    return Html::encode($model->ApplicationName);
                                },
                            ],
                            [
                                'attribute' => 'Method',
                                'label' => '函数',
                                'value' =>
                                function($model) {
                                    return Html::encode($model->Method);
                                },
                            ],
                            [
                                'attribute' => 'Parameter',
                                'label' => '参数',
                                'value' =>
                                function($model) {
                                    return Html::encode($model->Parameter);
                                },
                            ],
                            [
                                'attribute' => 'Content',
                                'label' => '详情',
                                'headerOptions' => ['class' => 'maxwidth'],
                                'value' =>
                                function($model) {
                                    return Html::encode($model->Content);
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
                        ],
                    ]);
                }
                Pjax::end();
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
        var logo = document.getElementById("logo");
    if(logo===null){
       window.location.href='/?url=<?= $_SERVER['REQUEST_URI'] ?>';
    }
    $(document).ready(function() {
        $(".ajax-link").on("click", function() {
            var thisurl = $(this).attr("href");
            htmlobj = $.ajax({url: thisurl, async: false});
            $("#ajax-content").html(htmlobj.responseText);
            return false;
        });
    });

</script>