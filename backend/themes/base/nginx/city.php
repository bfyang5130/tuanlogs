<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\services\NginxService;
use backend\services\NginxHightchartService;
use miloschuman\highcharts\Highcharts;

$this->title = '地区访问信息';

$search_date = Yii::$app->request->get("date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}
$table = Yii::$app->request->get("table");
if (empty($table)) {
    $table = 1;
}
if ($table == 1) {
    $table = NginxHightchartService::AccessStatistic;
} else {
    $table = NginxHightchartService::AccessStatisticOne;
}
$cityname = Yii::$app->request->get("cityname");
if (empty($cityname)) {
    $cityname = '广东';
} else {
    $cityname = urldecode($cityname);
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
                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5><?= $search_date . $cityname ?>城市访问情况：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h4>城市访问情况</h4>
                                                <?php
                                                //获得访问来源
                                                $pieflat_form = NginxHightchartService::getPiePlatHightChart($search_date, "DetailType1=:topT", [':topT' => $cityname], 'DetailType2', $table, '访问来源');
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
                                                            'text' => '城市访问情况'
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
                            </div>
                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5><?= $search_date . $cityname ?>24小时访问情况：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h4>24小时访问情况</h4>
                                                <?php
                                                //获得访问来源
                                                $pieflat_form = NginxHightchartService::getSplinePlatHightChart($search_date, "DetailType1=:topT", [':topT' => $cityname], 'CheckTime', $table, '访问来源');
                                                ?>
                                                <?=
                                                Highcharts::widget([
                                                    'options' => [
                                                        'chart' => [
                                                            'type' => 'spline',
                                                            'plotShadow' => true, //设置阴影
                                                            'height' => 450,
                                                        ],
                                                        'title' => [
                                                            'text' => '24小时城市访问情况'
                                                        ],
                                                        'xAxis' => [
                                                            'categories' => $pieflat_form['in_country']['categories'],
                                                            'title' => array('text' => null),
                                                        ],
                                                        'yAxis' => [
                                                            'min' => 0,
                                                            'title' => array('text' => ''),
                                                            'align' => 'high',
                                                            'labels' => array("overflow" => "justify")
                                                        ],
                                                        'credits' => [
                                                            'enabled' => false//不显示highCharts版权信息
                                                        ],
                                                        'plotOptions' => [
                                                            'spline' => [
                                                                'allowPointSelect' => true,
                                                                'cursor' => 'pointer',
                                                                'dataLabels' => [
                                                                    'enabled' => TRUE
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
