<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use miloschuman\highcharts\Highmaps;
use backend\services\NginxHightchartService;
use yii\web\JsExpression;

$this->registerJsFile('/base/js/cn-all-sar-taiwan.js', [
    'depends' => 'miloschuman\highcharts\HighchartsAsset'
]);
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
    <?php
    //获得访问来源
    $pieComeFrom = NginxHightchartService::getPieHightChart('2016-02-26', "TopType=:topT", [':topT' => 'user_ip_1'], 'DetailType1', NginxHightchartService::AccessStatisticOne, true);
    $data = [];
    $cityMap = \Yii::$app->params['cityMap'];
    foreach ($pieComeFrom['in_country']['series']['data'] as $key => $dataValue) {
        $data[] = ['hc-key' => $cityMap[$dataValue['name']], 'value' => $dataValue['y'], ['url' => $dataValue['url']]];
    }
    ?>
    <div class="body-content">
        <div class="panel panel-default">
            <?= $this->render('common_top.php', ['url' => '/visit/servicestatus']); ?>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <?=
                        Highmaps::widget([
                            'options' => [
                                'chart' => ['height' => 500],
                                'mapNavigation' => ['enabled' => true],
                                'title' => ['text' => '全国访问量'],
                                'colorAxis' => [
                                    'min' => 0,
                                    'minColor' => '#E6E7E8',
                                    'maxColor' => '#FF0000'
                                ],
                                'subtitle' => ['text' => '中国', 'floating' => TRUE, 'align' => 'right', 'y' => 50,],
                                'series' => [[
                                'name' => '省份',
                                'mapData' => new JsExpression('Highcharts.maps["countries/cn/custom/cn-all-sar-taiwan"]'),
                                'joinBy' => 'hc-key',
                                'data' => $data,
                                'dataLabels' => [
                                    'enabled' => true,
                                    'crop' => false,
                                    'overflow' => 'none',
                                    'format' => '{point.properties.cn-name}'
                                ]
                                    ],
                                ],
                                'credits' => [
                                    'text' => '大学霸',
                                    'href' => 'http://daxueba.net'
                                ]
                            ]
                                ]
                        );
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
