<?php

use backend\services\LogTypeService;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
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
                        'label' => '导航面板',
                        'url' => ['/ajax/error/index'],
                        'class' => 'ajax-link',
                    ]
                ]
            ]);
            ?>
        </ol>
        <?php
        //<div id="social" class="pull-right">
        //    <a href="#"><i class="fa fa-google-plus"></i></a>
        //    <a href="#"><i class="fa fa-facebook"></i></a>
        //    <a href="#"><i class="fa fa-twitter"></i></a>
        //    <a href="#"><i class="fa fa-linkedin"></i></a>
        //    <a href="#"><i class="fa fa-youtube"></i></a>
        //</div>
        ?>
    </div>
</div>
<?php
#获得日志统计记录
$dataProvider = LogTypeService::findCustomlogType();
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
                    <a class="ajax-link" href="<?= Url::toRoute('/ajax/customlog/add') ?>">
                        <i class="fa fa-plus"></i>
                    </a>
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
                <?php Pjax::begin(['id' => 'countries']); ?>
                <?=
                GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute' => 'logtype_id',
                            'label' => '日志类型'
                        ],
                        [
                            'attribute' => 'call_methods',
                            'label' => '调用函数'
                        ],
                        [
                            'attribute' => 'call_parameter',
                            'label' => '调用参数'
                        ],
                        [
                            'attribute' => 'add_time',
                            'label' => '添加时间'
                        ],
                        [
                            'attribute' => 'fit_time',
                            'label' => '处理时间'
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
                                        'class' => 'ajax-link',
                                    ];
                                    return Html::a('<button type="button" class="btn btn-sm btn-info">查看详情</button>', $url, $options);
                                }]
                        ],
                    ],
                ]);
                ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var logo = document.getElementById("logo");
    if (logo === null) {
        window.location.href = '/?url=<?= $_SERVER['REQUEST_URI'] ?>';
    }
    $(".ajax-link").on("click", function() {
        var thisurl = $(this).attr("href");
        htmlobj = $.ajax({url: thisurl, async: false});
        $("#ajax-content").html(htmlobj.responseText);
        return false;
    });
</script>