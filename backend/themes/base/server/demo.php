<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\web\View;

$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$this->title = '用户页面访问图';
$id = \Yii::$app->request->get('id');
if (!$id) {
    $id = 0;
}
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
                            <div class="col-lg-12" id="main"  style="width: 1240px;height:350px;">
                                <script type="text/javascript">
                                    var myChart = echarts.init(document.getElementById('main'));
                                    myChart.showLoading();
                                    $.get('/server/api.html?id=1&monitor_item=23779&date=2016-04-27', function(data) {
                                        myChart.hideLoading();

                                        myChart.setOption(option = {
                                        title: {
                                            text: data.title.text,
                                        },
                                        tooltip: {},
                                        legend: {
                                            data: [data.series.name]
                                        },
                                        dataZoom: [
                                            {
                                                type: 'slider',
                                                start: data.showlimit,
                                                end: 100 
                                            },
                                            {
                                                type: 'inside',
                                                start: data.showlimit,
                                                end: 100 
                                            }
                                        ],
                                        xAxis: {
                                            data: data.xAxis.data,
                                        },
                                        yAxis: [{
                                                name: data.series.name,
                                                type: 'value',
                                                max: 100,
                                                axisLabel: {
                                                    show: true,
                                                    interval: 'auto',
                                                    formatter: '{value} %'
                                                }
                                            }, ],
                                        series: [{
                                                name: data.series.name,
                                                itemStyle: {normal: {
                                                        label: {show: true, position: 'top', formatter: '{c} %'}
                                                    }},
                                                type: 'line',
                                                data: data.series.data,
                                            }]
                                    });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
