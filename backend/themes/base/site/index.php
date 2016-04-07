<?php

use yii\widgets\Breadcrumbs;
use backend\services\ErrorHightchartService;
use miloschuman\highcharts\Highcharts;
use backend\services\SqlHightchartService;
use backend\services\ZabbixHightchartService;
use backend\services\NginxHightchartService;
use yii\helpers\Url;

$this->title = '服务器数据总览';
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
                                        <tr>
                                            <td>
                                                <div class="panel-body">
                                                    <div class="tab-content">
                                                        <div class="tab-pane active">
                                                            <?php
                                                            //默认获得5个监控荐项进行近三个钟头的展示（这里可扩展为选择项）
                                                            $showLists5 = ZabbixHightchartService::find5Column();
                                                            if (!empty($showLists5)):
                                                                foreach ($showLists5 as $value):
                                                                    if (!isset($value['status'])):
                                                                        ?>
                                                                        <table class="table table-bordered table-striped table-condensed">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td><h5><?= $value['texttitle'] ?><span class="pull-right"><?= $value['server'] ?></span></h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
                                                                                        <?=
                                                                                        Highcharts::widget([
                                                                                            'options' => [
                                                                                                'chart' => [
                                                                                                    'type' => 'column',
                                                                                                    'plotShadow' => true, //设置阴影
                                                                                                    'height' => 350,
                                                                                                ],
                                                                                                'title' => [
                                                                                                    'text' => $value['texttitle']
                                                                                                ],
                                                                                                'xAxis' => [
                                                                                                    'categories' => $value['categories'],
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
                                                                                                'series' => [$value['series']]
                                                                                            ]
                                                                                        ]);
                                                                                        ?>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                        <?php
                                                                    else:
                                                                        ?>
                                                                        <table class="table table-bordered table-striped table-condensed">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td><h5><?= $value['texttitle'] ?><span class="pull-right"><?= $value['server'] ?></span></h5></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>
            <?= $value['error'] ?>
                                                                                    </td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    <?php
                                                                    endif;
                                                                endforeach;
                                                            endif;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>