<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\services\NginxService;
use backend\services\NginxHightchartService;
use miloschuman\highcharts\Highcharts;

$this->title = 'nginx访问统计';

$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}
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
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-6">
                                <?php
                                //获得21今日访问情况
                                $userVisits = NginxService::findOneTypeAmounts($search_date, 'status', NginxService::AccessStatistic);
                                if (!empty($userVisits)):
                                    //获得21今日总流量
                                    $userFlow = NginxService::findOneTypeAmounts($search_date, 'content_size', NginxService::AccessStatistic);
                                    //获得21今日总耗时
                                    $userTime = NginxService::findOneTypeAmounts($search_date, 'take_time', NginxService::AccessStatistic);
                                    ?>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td colspan="6"><h5>21代理服务器</h5></td>
                                            </tr>
                                            <tr>
                                                <td><?= $search_date ?>访问量：</td><td><?= $userVisits ?></td>
                                                <td>总流量：</td><td><?= round($userFlow / 1024 / 1024, 2) ?>M</td>
                                                <td>网站吞吐量：</td><td><?= round($userFlow / 1024 / 1024 * 1000 / $userTime, 2) ?>M/s</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>访问来源：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>国内来源</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieComeFrom = NginxHightchartService::getPieHightChart($search_date, "TopType=:topT", [':topT' => 'user_ip_1'], 'DetailType1', NginxHightchartService::AccessStatistic, true);
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '国内访问来源'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true,
                                                                ],
                                                                'series' => [
                                                                    'cursor' => 'pointer',
                                                                    'point' => [
                                                                        'events' => [
                                                                            'click' => new \yii\web\JsExpression('function () {window.open(this.options.url);}')
                                                                        ]
                                                                    ],
                                                                ]
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieComeFrom['in_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>国外来源</h4>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 650,
                                                            ],
                                                            'title' => [
                                                                'text' => '国外访问来源'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieComeFrom['out_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>操作平台：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>操作平台</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "TopType=:topT", [':topT' => 'plat_form'], 'DetailType1', NginxHightchartService::AccessStatistic, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '操作系统'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieflat_form['in_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>访问协议：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>协议方式</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "TopType=:topT", [':topT' => 'protocol'], 'DetailType1', NginxHightchartService::AccessStatistic, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '协议方式'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieflat_form['in_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>状态：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>响应状态</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "TopType=:topT", [':topT' => 'status'], 'DetailType1', NginxHightchartService::AccessStatistic, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '响应状态'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieflat_form['in_country']['series']]
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
                                                <td><h5>17代理服务器</h5></td>
                                            </tr>
                                            <tr>
                                                <td>这一天的数据没有记录</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                                endif;
                                ?>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                //获得17今日访问情况
                                $userVisits = NginxService::findOneTypeAmounts($search_date, 'status', NginxService::AccessStatisticOne);
                                if (!empty($userVisits)):
//获得17今日总流量
                                    $userFlow = NginxService::findOneTypeAmounts($search_date, 'content_size', NginxService::AccessStatisticOne);
                                    //获得17今日总耗时
                                    $userTime = NginxService::findOneTypeAmounts($search_date, 'take_time', NginxService::AccessStatisticOne);
                                    ?>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td colspan="6"><h5>17代理服务器</h5></td>
                                            </tr>
                                            <tr>
                                                <td><?= $search_date ?>访问量：</td><td><?= $userVisits ?></td>
                                                <td>总流量：</td><td><?= round($userFlow / 1024 / 1024, 2) ?>M</td>
                                                <td>网站吞吐量：</td><td><?= round($userFlow / 1024 / 1024 * 1000 / $userTime, 2) ?>M/s</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>访问来源：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>国内来源</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieComeFrom1 = NginxHightchartService::getPieHightChart($search_date, "TopType=:topT", [':topT' => 'user_ip_1'], 'DetailType1', NginxHightchartService::AccessStatisticOne, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '国内访问来源'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieComeFrom1['in_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>国外来源</h4>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 650,
                                                            ],
                                                            'title' => [
                                                                'text' => '国外访问来源'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieComeFrom1['out_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>操作平台：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>操作平台</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "TopType=:topT", [':topT' => 'plat_form'], 'DetailType1', NginxHightchartService::AccessStatisticOne, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '操作系统'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieflat_form['in_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>访问协议：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>访问协议</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "TopType=:topT", [':topT' => 'protocol'], 'DetailType1', NginxHightchartService::AccessStatisticOne, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '访问协议'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieflat_form['in_country']['series']]
                                                        ]
                                                    ]);
                                                    ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>状态：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h4>响应状态</h4>
                                                    <?php
                                                    //获得访问来源
                                                    $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "TopType=:topT", [':topT' => 'status'], 'DetailType1', NginxHightchartService::AccessStatisticOne, '访问来源');
                                                    ?>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'pie',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 450,
                                                            ],
                                                            'title' => [
                                                                'text' => '响应状态'
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'pie' => [
                                                                    'allowPointSelect' => true,
                                                                    'cursor' => 'pointer',
                                                                    'dataLabels' => [
                                                                        'enabled' => false
                                                                    ],
                                                                    'showInLegend' => true
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "bottom",
                                                            ],
                                                            'series' => [$pieflat_form['in_country']['series']]
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
                                                <td><h5>17代理服务器</h5></td>
                                            </tr>
                                            <tr>
                                                <td>这一天的数据没有记录</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
