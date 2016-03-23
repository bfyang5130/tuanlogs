<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use miloschuman\highcharts\Highcharts;
use backend\services\ServerHightchartService;

$this->title = '服务器状态信息';
$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}

$ip = Yii::$app->request->get("ip");
if (empty($ip)) {
    $ip = '192.168.8.190';
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
            <?= $this->render('common_top.php',['url'=>'/visit/servicestatus']); ?>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td><h5>服务器状态信息：</h5></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?php
                                            //获得数据
                                            $serverstatus = ServerHightchartService::findOneServerColumn($ip, $search_date);
                                            if (!empty($serverstatus)):
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
                                                            'text' => '服务器状态'
                                                        ],
                                                        'xAxis' => [
                                                            'categories' => $serverstatus['in_country']['categories'],
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
                                                                    'enabled' => true,
                                                                    'color' => 'black',
                                                                ],
                                                            ],
                                                        ],
                                                        'legend' => [
                                                            'verticalAlign' => "top",
                                                            'floating' => true,
                                                            'y' => 20,
                                                        ],
                                                        'series' => $serverstatus['in_country']['memory']['series'],
                                                    ]
                                                ]);
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>CPU负载信息：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h4>CPU负载信息</h4>
                                                <?=
                                                Highcharts::widget([
                                                    'options' => [
                                                        'chart' => [
                                                            'type' => 'spline',
                                                            'plotShadow' => true, //设置阴影
                                                            'height' => 350,
                                                        ],
                                                        'title' => [
                                                            'text' => 'CPU负载信息(%)'
                                                        ],
                                                        'xAxis' => [
                                                            'categories' => $serverstatus['in_country']['categories'],
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
                                                                //'stacking' => 'normal',
                                                                'dataLabels' => [
                                                                    'enabled' => true,
                                                                    'color' => 'black',
                                                                ],
                                                            ],
                                                        ],
                                                        'legend' => [
                                                            'verticalAlign' => "top",
                                                            'floating' => true,
                                                            'y' => 20,
                                                        ],
                                                        'series' => $serverstatus['in_country']['cpu']['series'],
                                                    ]
                                                ]);
                                            else:
                                                echo '这一天没有对应的数据';
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
