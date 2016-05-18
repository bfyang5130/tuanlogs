<?php

use yii\widgets\Breadcrumbs;
use backend\services\ErrorHightchartService;
use miloschuman\highcharts\Highcharts;
use backend\services\SqlHightchartService;
use backend\services\ZabbixHightchartService;
use backend\services\NginxHightchartService;
use yii\helpers\Url;
use yii\web\View;

$this->title = '服务器数据总览';
$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
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
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                #获得所有错误类型最近五天的趋势
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><h5>程序统计<a class="pull-right" target="_blank" href="<?= Url::toRoute('/errors/index') ?>">查看错误日志</a></h5></td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                <?php
                                                #获得五个错误最多的错误信息
                                                $error5Nums = ErrorHightchartService::find5Column('logtype=0', [], 5, 'logtotal');
                                                if (!empty($error5Nums)):
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'column',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => '最多错误的五个应用'
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $error5Nums['in_country']['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'column' => [
                                                                    'stacking' => 'normal',
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                                'series' => [
                                                                    'cursor' => 'pointer',
                                                                    'events' => [
                                                                        'click' => new yii\web\JsExpression('function(e){ window.open(e.point.url);}')
                                                                    ],
                                                                ]
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "top",
                                                                'floating' => true,
                                                                'y' => 20,
                                                            ],
                                                            'series' => [$error5Nums['in_country']['series']]
                                                        ]
                                                    ]);
                                                endif;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                #获得五个错误最多的错误信息
                                                $errorLines = ErrorHightchartService::findAllLine('logtype=0', [], 5, 'logtotal');
                                                if (!empty($errorLines)):
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'spline',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => '最近五天错误趋势图'
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $errorLines['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'spline' => [
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                            ],
                                                            'series' => $errorLines['series']
                                                        ]
                                                    ]);
                                                endif;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                #获得所有错误类型最近五天的趋势
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><h5>SQL统计<a class="pull-right" target="_blank" href="<?= Url::toRoute('/sql/longtimesql') ?>">查看慢日志</a></h5></td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                <?php
                                                #获得五个错误最多的错误信息
                                                $error5Nums = SqlHightchartService::find5Column('', [], 'databasetype');
                                                if (!empty($error5Nums)):
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'column',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => '超过800毫秒的查询'
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $error5Nums['in_country']['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'column' => [
                                                                    'stacking' => 'normal',
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                                'series' => [
                                                                    'cursor' => 'pointer',
                                                                    'events' => [
                                                                        'click' => new yii\web\JsExpression('function(e){ window.open(e.point.url);}')
                                                                    ],
                                                                ]
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "top",
                                                                'floating' => true,
                                                                'y' => 20,
                                                            ],
                                                            'series' => [$error5Nums['in_country']['series']]
                                                        ]
                                                    ]);
                                                endif;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                #获得五个错误最多的错误信息
                                                $errorLines = SqlHightchartService::findAllLine();
                                                if (!empty($errorLines)):
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'spline',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => '慢日志最近五天趋势'
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $errorLines['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'spline' => [
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                            ],
                                                            'series' => $errorLines['series']
                                                        ]
                                                    ]);
                                                endif;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>服务器性能监控<a class="pull-right" target="_blank" href="<?= Url::toRoute('/server/index') ?>">查看更多监控选项</a></h5></td>
                                        </tr>
                                        <?php
                                        //循环取得三个推荐服务器性能显示
                                        ?>
                                        <?php
                                        $showLists5 = \backend\services\ServerStatusService::find5Column();
                                        if (!empty($showLists5)):
                                            $showI = 1;
                                            foreach ($showLists5 as $oneShowItem):
                                                ?>
                                        <tr>
                                            <td><?= $oneShowItem->monitor_name ?></td>
                                        </tr>
                                                <tr>
                                                    <td>
                                                        <div class="panel-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="main<?= $showI ?>" style="height:250px;">
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function() {
                                                                            
                                                                             
                                                                            var <?= "myChart".$showI ?> = echarts.init(document.getElementById('main<?= $showI ?>'));
                                                                            <?= "myChart".$showI ?>.showLoading();

                                                                            $.get('/server/api.html?fc=twodayfit&monitor_id=<?= $oneShowItem->id ?>&date=<?= date("Y-m-d") ?>', function(data) {
                                                                                <?= "myChart".$showI ?>.hideLoading();
                                                                                <?= "myChart".$showI ?>.setOption(option = {
                                                                                    tooltip: {
                                                                                        trigger: 'axis',
                                                                                        axisPointer: {// 坐标轴指示器，坐标轴触发有效
                                                                                            type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                                                                        }
                                                                                    },
                                                                                    legend: {
                                                                                        data: data.legenddata,
                                                                                        show: true
                                                                                    },
                                                                                    grid: {
                                                                                        left: '3%',
                                                                                        right: '4%',
                                                                                        bottom: '3%',
                                                                                        containLabel: true
                                                                                    },
                                                                                    xAxis: [
                                                                                        {
                                                                                            type: 'category',
                                                                                            data: data.xAxisdata,
                                                                                        }
                                                                                    ],
                                                                                    yAxis: [
                                                                                        {
                                                                                            type: 'value',
                                                                                            max: 100,
                                                                                            axisLabel: {
                                                                                                show: true,
                                                                                                interval: 'auto',
                                                                                                formatter: '{value} %'
                                                                                            }
                                                                                        }
                                                                                    ],
                                                                                    series: data.series
                                                                                });
                                                                            });
                                                                            <?= "myChart".$showI ?>.on('click', function(parmas) {
                                                                                //这里的parmas.name是一个时间数值，可以用来处理对应的时间段数据
                                                                                //获得详细指定的时间段数据，并更改图形内的数据
                                                                                var fitnametime = parmas.name;
                                                                                if (fitnametime.length > 5) {
                                                                                    //返回直接跳转回当值页面就算了
                                                                                    if (confirm("确定返回上层数据吗？")) {
                                                                                        location.reload();
                                                                                    }
                                                                                } else {
                                                                                    alert("数据加载中...请稍侯");
                                                                                    $.get('/server/api.html?fc=detail&monitor_id=<?= $oneShowItem->id ?>&date=<?= date("Y-m-d") ?>&detialtime=' + parmas.name, function(data) {

                                                                                        if (data.seriesdata1 == "undefined") {
                                                                                            alert("无法获得这个时间点的数据");
                                                                                        } else {
                                                                                            var newoption = <?= "myChart".$showI ?>.getOption();
                                                                                            newoption.series[0].data = data.seriesdata1;
                                                                                            newoption.series[1].data = data.seriesdata2;
                                                                                            newoption.xAxis[0].data = data.xAxisdata;
                                                                                            newoption.dataZoom = data.dataZoom;
                                                                                            <?= "myChart".$showI ?>.setOption(newoption);
                                                                                        }
                                                                                    });
                                                                                }
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $showI++;
                                            endforeach;
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                #获得所有错误类型最近五天的趋势
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><h5>页面统计信息<a class="pull-right" target="_blank" href="<?= Url::toRoute('/nginx/errorstatus') ?>">查看更多信息</a></h5></td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                <?php
                                                #获得五个错误最多的错误信息
                                                $error5Nums = NginxHightchartService::pageAttack();
                                                if (!empty($error5Nums)):
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'column',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => '访问出错信息'
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $error5Nums['in_country']['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'column' => [
                                                                    'stacking' => 'normal',
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                                'series' => [
                                                                    'cursor' => 'pointer',
                                                                    'events' => [
                                                                        'click' => new yii\web\JsExpression('function(e){ window.open(e.point.url);}')
                                                                    ],
                                                                ]
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => 'top',
                                                                'floating' => true,
                                                                'y' => 20,
                                                            ],
                                                            'series' => [$error5Nums['in_country']['series']]
                                                        ]
                                                    ]);
                                                endif;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                #获得五个错误最多的错误信息
                                                $errorLines = NginxHightchartService::findAllLine();
                                                if (!empty($errorLines)):
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'spline',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => '错误页面显示'
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $errorLines['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'spline' => [
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                            ],
                                                            'series' => $errorLines['series']
                                                        ]
                                                    ]);
                                                endif;
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>