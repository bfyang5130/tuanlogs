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

                                    $.get('/server/api.html?fc=twodayfit&monitor_id=1&date=2016-05-17', function(data) {
                                        myChart.hideLoading();
                                        myChart.setOption(option = {
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
                                    myChart.on('click', function (parmas) {
                                    alert(parmas.name);
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
