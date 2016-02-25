<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use nirvana\showloading\ShowLoadingAsset;
use miloschuman\highcharts\Highcharts;

ShowLoadingAsset::register($this);

$this->title = '错误日志-日统计';
$page = Yii::$app->request->get("page");
$search_date = Yii::$app->request->get("search_date");
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
                <div class="form-inline" role="toolbar">

                    <div class="box-body text-center">
                        <div class="btn-group pull-left" role="group" aria-label="First group">
                            <a href="<?= Url::toRoute(['/site/countday', "page" => $pre_page]) ?>" class="btn btn-default">上一页</a>
                            <a href="<?= Url::toRoute(['/site/countday', "page" => $next_page]) ?>" class="btn btn-default">下一页</a>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail2">时间:</label>
                            <?=
                            \yii\jui\DatePicker::widget([
                                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                                'attribute' => 'start_date',
                                'language' => 'zh-CN',
                                'dateFormat' => 'yyyy-MM-dd',
                                'value' => empty($search_date) ? date('Y-m-d') : $search_date,
                                'clientOptions' => [
                                    'minDate' => '2015-01-01',
                                    'maxDate' => date("Y-m-d"),
                                    'onSelect' => new \yii\web\JsExpression(
                                            "function (dateText, inst) {
                                            var url = '/site/countday.html?search_date='+ dateText;
                                            location.href = url;
                                        }"
                                    ),
                                ],
                            ]);
                            ?>
                        </div>
                        <div class="btn-group pull-right" role="group" aria-label="First group">
                            <a href="<?= Url::toRoute('/site/errorgraph') ?>" class="btn btn-default">总统计</a>
                            <a href="<?= Url::toRoute('/site/countday') ?>" class="btn btn-default">日统计</a>
                            <a href="<?= Url::toRoute('/site/countmonth') ?>" class="btn btn-default">月统计</a>
                            <a href="<?= Url::toRoute(['/site/index']) ?>" class="btn btn-default pull-right">返回列表</a>
                        </div>

                    </div>

                </div>
                <div class="tab-pane" style="margin-top:20px;">
                    <table class="table table-bordered table-striped table-condensed">
                        <tbody>
                            <tr>
                                <td colspan="3">
                                    <div class="content form-inline">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php
                                                echo Highcharts::widget([
                                                    'options' => [
                                                        'chart' => [
                                                            'type' => 'bar',
                                                            'plotShadow' => false, //设置阴影
                                                            'height' => 1200,
                                                        ],
                                                        'title' => [
                                                            'text' => '错误日志日统计'
                                                        ],
                                                        'credits' => [
                                                            'enabled' => false//不显示highCharts版权信息
                                                        ],
                                                        'xAxis' => [
                                                            'categories' => $appnames,
                                                            'title' => array('text' => null),
                                                        ],
                                                        'yAxis' => [
                                                            'min' => 0,
                                                            'title' => array('text' => ''),
                                                            'align' => 'high',
                                                            'labels' => array("overflow" => "justify")
                                                        ],
                                                        'plotOptions' => [
                                                            'bar' => [
                                                                'dataLabels' => [
                                                                    'enabled' => true
                                                                ]
                                                            ],
                                                            'series' => [
                                                                'cursor' => 'pointer',
                                                                'events' => array("click" => new \yii\web\JsExpression(
                                                                            'function(e){
                                             var search_data = this.name ;
                                             var arr = search_data.split("-");
                                             var newdt = new Date(Number(arr[0]),Number(arr[1])-1,Number(arr[2])+1);
                                             var end_month = newdt.getMonth()+1 ;
                                             var end_day = newdt.getDate() ;
                                             var end_year = newdt.getFullYear() ;
                                             var end_date = end_year+"-"+end_month+"-"+end_day ;
                                            var target_url = "/site/index.html?ErrorLogSearch[start_date]="+this.name+"&ErrorLogSearch[end_date]="+end_date+"&ErrorLogSearch[ApplicationId]="+e.point.category;
                                            window.open(target_url);
                                         }'
                                                                    ))
                                                            ]
                                                        ],
                                                        'legend' => [
                                                            'layout' => 'horizontal',
                                                            'align' => 'center',
                                                            'verticalAlign' => 'top',
                                                            'floating' => true,
//                                'x'=>90,
                                                            'y' => 20,
                                                            'borderWidth' => 1,
                                                            'backgroundColor' => '#FFFFFF',
                                                            'shadow' => true,
                                                        ],
                                                        'tooltip' => [
                                                            'enabled' => false,
                                                        ],
                                                        'series' => $series
                                                    ]
                                                ]);
                                                ?>
                                            </div>
                                        </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //        $(document).ready(function() {
    //        });
</script>