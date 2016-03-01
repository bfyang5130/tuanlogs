<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use miloschuman\highcharts\Highcharts;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use mootensai\components\JsBlock;

$this->title = '日志列表';

$page = Yii::$app->request->get("page");
$search_date = Yii::$app->request->get("search_date");
#获得日志统计记录
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
    $id = \Yii::$app->controller->action->id;
    ?>
    <div class="body-content">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        <div class="content form-inline">
                                            <div class="row">
                                                <div class="col-md-12">

                                                    <div class="btn-group pull-left" role="group" aria-label="First group">
                                                        <a href="<?= Url::toRoute(['/sql/sqlgraph', "page" => $pre_page, 'search_date' => $search_date]) ?>" class="btn btn-default">上一页</a>
                                                        <a href="<?= Url::toRoute(['/sql/sqlgraph', "page" => $next_page, 'search_date' => $search_date]) ?>" class="btn btn-default">下一页</a>
                                                    </div>

                                                    <div class="form-group pull-right">
                                                        <label for="exampleInputEmail2">更多统计:</label>
                                                        <?=
                                                        Html::dropDownList('更多统计:', 'databaseid', backend\models\forms\TableFitForm::findDatabase(), ['class' => 'form-control','onChange'=>'window.location.href="/sql/database.html?type="+this.value;']);
                                                        ?>
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
                                            var url = '/sql/sqlgraph.html?search_date='+ dateText;
                                            location.href = url;
                                        }"
                                                                ),
                                                            ],
                                                        ]);
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php if (!empty($appnames)): ?>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div class="content form-inline">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?php
                                                        echo Highcharts::widget([
                                                            'options' => [
                                                                'chart' => [
                                                                    'type' => 'column',
                                                                    'plotShadow' => false, //设置阴影
                                                                    'height' => 350,
                                                                ],
                                                                'title' => [
                                                                    'text' => '总访问量（次/天）'
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
                                                                ],
                                                                'legend' => [
                                                                    'layout' => 'vertical',
                                                                    'align' => 'right',
                                                                    'verticalAlign' => 'top',
                                                                    'x' => -40,
                                                                    'y' => 100,
                                                                    'floating' => true,
                                                                    'borderWidth' => 1,
                                                                    'backgroundColor' => '#FFFFFF',
                                                                    'shadow' => true,
                                                                ],
                                                                'tooltip' => [
                                                                    'enabled' => false,
                                                                ],
                                                                'legend' => [
                                                                    'verticalAlign' => "bottom",
                                                                ],
                                                                'series' => $series
                                                            ]
                                                        ]);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php
                                                        echo Highcharts::widget([
                                                            'options' => [
                                                                'chart' => [
                                                                    'type' => 'column',
                                                                    'plotShadow' => false, //设置阴影
                                                                    'height' => 350,
                                                                ],
                                                                'title' => [
                                                                    'text' => '全天访问频率（次/秒）'
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
                                                                ],
                                                                'legend' => [
                                                                    'layout' => 'vertical',
                                                                    'align' => 'right',
                                                                    'verticalAlign' => 'top',
                                                                    'x' => -40,
                                                                    'y' => 100,
                                                                    'floating' => true,
                                                                    'borderWidth' => 1,
                                                                    'backgroundColor' => '#FFFFFF',
                                                                    'shadow' => true,
                                                                ],
                                                                'tooltip' => [
                                                                    'enabled' => false,
                                                                ],
                                                                'legend' => [
                                                                    'verticalAlign' => "bottom",
                                                                ],
                                                                'series' => $series1
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
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div class="content form-inline">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?php
                                                        echo Highcharts::widget([
                                                            'options' => [
                                                                'chart' => [
                                                                    'type' => 'spline',
                                                                    'plotShadow' => false, //设置阴影
                                                                    'height' => 350,
                                                                ],
                                                                'title' => [
                                                                    'text' => '访问量（次/时）'
                                                                ],
                                                                'credits' => [
                                                                    'enabled' => false//不显示highCharts版权信息
                                                                ],
                                                                'xAxis' => [
                                                                    'categories' => $appnameshourshow,
                                                                    'title' => array('text' => null),
                                                                ],
                                                                'yAxis' => [
                                                                    'min' => 0,
                                                                    'title' => array('text' => ''),
                                                                    'align' => 'high',
                                                                    'labels' => array("overflow" => "justify")
                                                                ],
                                                                'plotOptions' => [
                                                                    'spline' => [
                                                                        'dataLabels' => [
                                                                            'enabled' => true
                                                                        ]
                                                                    ],
                                                                ],
                                                                'legend' => [
                                                                    'layout' => 'vertical',
                                                                    'align' => 'right',
                                                                    'verticalAlign' => 'top',
                                                                    'x' => -40,
                                                                    'y' => 100,
                                                                    'floating' => true,
                                                                    'borderWidth' => 1,
                                                                    'backgroundColor' => '#FFFFFF',
                                                                    'shadow' => true,
                                                                ],
                                                                'tooltip' => [
                                                                    'enabled' => false,
                                                                ],
                                                                'legend' => [
                                                                    'verticalAlign' => "bottom",
                                                                ],
                                                                'series' => $series2
                                                            ]
                                                        ]);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php
                                                        echo Highcharts::widget([
                                                            'options' => [
                                                                'chart' => [
                                                                    'type' => 'spline',
                                                                    'plotShadow' => false, //设置阴影
                                                                    'height' => 350,
                                                                ],
                                                                'title' => [
                                                                    'text' => '访问频率[次/秒]'
                                                                ],
                                                                'credits' => [
                                                                    'enabled' => false//不显示highCharts版权信息
                                                                ],
                                                                'xAxis' => [
                                                                    'categories' => $appnameshourshow,
                                                                    'title' => array('text' => null),
                                                                ],
                                                                'yAxis' => [
                                                                    'min' => 0,
                                                                    'title' => array('text' => ''),
                                                                    'align' => 'high',
                                                                    'labels' => array("overflow" => "justify")
                                                                ],
                                                                'plotOptions' => [
                                                                    'spline' => [
                                                                        'dataLabels' => [
                                                                            'enabled' => true
                                                                        ]
                                                                    ],
                                                                ],
                                                                'legend' => [
                                                                    'layout' => 'vertical',
                                                                    'align' => 'right',
                                                                    'verticalAlign' => 'top',
                                                                    'x' => -40,
                                                                    'y' => 100,
                                                                    'floating' => true,
                                                                    'borderWidth' => 1,
                                                                    'backgroundColor' => '#FFFFFF',
                                                                    'shadow' => true,
                                                                ],
                                                                'tooltip' => [
                                                                    'enabled' => false,
                                                                ],
                                                                'legend' => [
                                                                    'verticalAlign' => "bottom",
                                                                ],
                                                                'series' => $series3
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
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div class="content form-inline">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <?php
                                                        echo Highcharts::widget([
                                                            'options' => [
                                                                'chart' => [
                                                                    'type' => 'spline',
                                                                    'plotShadow' => false, //设置阴影
                                                                    'height' => 350,
                                                                ],
                                                                'title' => [
                                                                    'text' => '每小时总耗时(毫秒)'
                                                                ],
                                                                'credits' => [
                                                                    'enabled' => false//不显示highCharts版权信息
                                                                ],
                                                                'xAxis' => [
                                                                    'categories' => $appnameshourshow,
                                                                    'title' => array('text' => null),
                                                                ],
                                                                'yAxis' => [
                                                                    'min' => 0,
                                                                    'title' => array('text' => ''),
                                                                    'align' => 'high',
                                                                    'labels' => array("overflow" => "justify")
                                                                ],
                                                                'plotOptions' => [
                                                                    'spline' => [
                                                                        'dataLabels' => [
                                                                            'enabled' => true
                                                                        ]
                                                                    ],
                                                                ],
                                                                'legend' => [
                                                                    'layout' => 'vertical',
                                                                    'align' => 'right',
                                                                    'verticalAlign' => 'top',
                                                                    'x' => -40,
                                                                    'y' => 100,
                                                                    'floating' => true,
                                                                    'borderWidth' => 1,
                                                                    'backgroundColor' => '#FFFFFF',
                                                                    'shadow' => true,
                                                                ],
                                                                'tooltip' => [
                                                                    'enabled' => false,
                                                                ],
                                                                'legend' => [
                                                                    'verticalAlign' => "bottom",
                                                                ],
                                                                'series' => $series4
                                                            ]
                                                        ]);
                                                        ?>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <?php
                                                        echo Highcharts::widget([
                                                            'options' => [
                                                                'chart' => [
                                                                    'type' => 'spline',
                                                                    'plotShadow' => false, //设置阴影
                                                                    'height' => 350,
                                                                ],
                                                                'title' => [
                                                                    'text' => '平均每秒耗时[毫秒]'
                                                                ],
                                                                'credits' => [
                                                                    'enabled' => false//不显示highCharts版权信息
                                                                ],
                                                                'xAxis' => [
                                                                    'categories' => $appnameshourshow,
                                                                    'title' => array('text' => null),
                                                                ],
                                                                'yAxis' => [
                                                                    'min' => 0,
                                                                    'title' => array('text' => ''),
                                                                    'align' => 'high',
                                                                    'labels' => array("overflow" => "justify")
                                                                ],
                                                                'plotOptions' => [
                                                                    'spline' => [
                                                                        'dataLabels' => [
                                                                            'enabled' => true
                                                                        ]
                                                                    ],
                                                                ],
                                                                'legend' => [
                                                                    'layout' => 'vertical',
                                                                    'align' => 'right',
                                                                    'verticalAlign' => 'top',
                                                                    'x' => -40,
                                                                    'y' => 100,
                                                                    'floating' => true,
                                                                    'borderWidth' => 1,
                                                                    'backgroundColor' => '#FFFFFF',
                                                                    'shadow' => true,
                                                                ],
                                                                'tooltip' => [
                                                                    'enabled' => false,
                                                                ],
                                                                'legend' => [
                                                                    'verticalAlign' => "bottom",
                                                                ],
                                                                'series' => $series5
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
                <?php else: ?>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <table class="table table-bordered table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td colspan="3">
                                            <div class="content form-inline">
                                                <div class="row">
                                                    <div class="col-md-12 text-center">
                                                        当前没有数据
                                                    </div>
                                                </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>