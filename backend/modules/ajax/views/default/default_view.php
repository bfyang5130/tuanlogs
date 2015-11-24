<?php

use backend\services\ErrorLogService;
use backend\services\TraceLogService;
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
                        'class'=>'ajax-link',
                    ]
                ]
            ]);
            ?>
        </ol>
        <div id="social" class="pull-right">
            <a href="#"><i class="fa fa-google-plus"></i></a>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
            <a href="#"><i class="fa fa-youtube"></i></a>
        </div>
    </div>
</div>
<?php
#获得具体日志统计记录
$p_get = Yii::$app->request->get();
if (isset($p_get['type']) && $p_get['type'] != 1) {
    $dataProvider = ErrorLogService::findErrorLogByAppId();
} else {
    $dataProvider = TraceLogService::findTraceLogByAppId();
}
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
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'ApplicationName',
                                'label' => '类型',
                            ],
                            [
                                'attribute' => 'Method',
                                'label' => '函数'
                            ],
                            [
                                'attribute' => 'Parameter',
                                'label' => '参数'
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
                                'attribute' => 'AddDate',
                                'label' => '日期',
                            ],
                        ],
                    ]);
                } else {
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'attribute' => 'ApplicationName',
                                'label' => '类型',
                            ],
                            [
                                'attribute' => 'Method',
                                'label' => '函数'
                            ],
                            [
                                'attribute' => 'Parameter',
                                'label' => '参数'
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
                                'attribute' => 'AddDate',
                                'label' => '日期',
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
    $(".ajax-link").on("click", function() {
        var thisurl = $(this).attr("href");
        htmlobj = $.ajax({url: thisurl, async: false});
        $("#ajax-content").html(htmlobj.responseText);
        return false;
    });
</script>