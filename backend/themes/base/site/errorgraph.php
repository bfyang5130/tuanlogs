<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use nirvana\showloading\ShowLoadingAsset;
use miloschuman\highcharts\Highcharts ;

ShowLoadingAsset::register($this);

$this->title = '图形显示';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
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

    <div class="body-content" id="text_body_c">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group pull-right" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute('/site/index') ?>" class="btn btn-default">列表</a>
                        <a href="<?= Url::toRoute('/site/errorgraph') ?>" class="btn btn-default">图形</a>
                    </div>
                </div>
                <div>
                    <?php
                    echo Highcharts::widget([
                        'options'=>[
                            'chart' => [
                                'defaultSeriesType'=> 'column',
                                'plotShadow'=> false ,//设置阴影
                                'height'=>450,
                            ],
                            'title' => [
                                'text' => '错误日志'
                            ],
                            'credits' => [
                                'enabled'=>false//不显示highCharts版权信息
                            ],
                            'xAxis' => [
                                'categories' => $appliaction_list,
                            ],
                            'yAxis' => [
                                'min' => 0,
                                'title' => array('text' => '')
                            ],
                            'plotOptions'=>[
                                'series'=>[
                                    'dataLabels'=>[
                                        'enabled'=>true
                                    ]
                                ]
                            ],
                            'tooltip'=>[
                                'enabled'=>false,
                            ],
                            'legend' =>[
                                'verticalAlign'=>"bottom" ,
                            ],
                            'series' => [
                                ['name' => '数量', 'data' => $error_num_list, 'color' => '#DD0000'],
                            ]
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //    $(document).ready(function() {
    //        $("#text_body_c").showLoading();
    //        $.ajax({
    //            url: "/site/getdata.html?type=1",
    //            dataType: 'html',
    //            success: function() {
    //                $(this).addClass("done");
    //            },
    //            error: function() {
    //                $(this).addClass("done");
    //            }
    //        });
    //    });
</script>