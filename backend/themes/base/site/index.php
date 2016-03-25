<?php

use yii\widgets\Breadcrumbs;
use backend\services\ErrorHightchartService;
use miloschuman\highcharts\Highcharts;
use backend\services\SqlHightchartService;
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
                                            <td colspan="2"><h5>程序统计</h5></td>
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
                                            <td colspan="2"><h5>SQL统计</h5></td>
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
                            <div class="col-lg-6">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>网络统计</h5></td>
                                        </tr>
                                        <tr>
                                            <td>这一天的数据没有记录</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>页面统计</h5></td>
                                        </tr>
                                        <tr>
                                            <td>这一天的数据没有记录</td>
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