<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\web\View;

$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);

//默认时间为当天
$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}
$this->title = $search_date.'访问情况';
//默认得到的是访问量的统计
$fc = Yii::$app->request->get("fc");
if (empty($fc)) {
    $fc = 'totalvisit';
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
            <?= $this->render('second_top.php', ['url' => '/visit/onedtail.html?fc='.$fc]); ?>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-12" id="main"  style="width: 1240px;height:300px;">
                                <script type="text/javascript">
                                    var myChart = echarts.init(document.getElementById('main'));
                                    myChart.showLoading();

                                    $.get('/visit/api.html?fc=<?= $fc ?>&proxy=17&date=<?= $search_date ?>', function(data) {
                                        myChart.hideLoading();
                                        myChart.setOption(option = {
                                            title: {
                                                text: '代理17全天访问情况',
                                                left: 'left',
                                                top: 'top'
                                            },
                                            tooltip: {
                                                trigger: 'axis',
                                                axisPointer: {// 坐标轴指示器，坐标轴触发有效
                                                    type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                                }
                                            },
                                            legend: {
                                                data: data.legend,
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
                                                    data: data.xdata,
                                                }
                                            ],
                                            yAxis: [
                                                {
                                                    type: 'value',
                                                    axisLabel: {
                                                        show: true,
                                                        interval: 'auto',
                                                    }
                                                }
                                            ],
                                            series: [
                                                {
                                                    name: data.legend,
                                                    type: 'bar',
                                                    data: data.seriesdata
                                                }
                                            ]
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-12" id="main21"  style="width: 1240px;height:300px;">
                                <script type="text/javascript">
                                    var myChart21 = echarts.init(document.getElementById('main21'));
                                    myChart21.showLoading();

                                    $.get('/visit/api.html?fc=<?= $fc ?>&proxy=21&date=<?= $search_date ?>', function(data) {
                                        myChart21.hideLoading();
                                        myChart21.setOption(option = {
                                            title: {
                                                text: '代理21全天访问情况',
                                                left: 'left',
                                                top: 'top'
                                            },
                                            tooltip: {
                                                trigger: 'axis',
                                                axisPointer: {// 坐标轴指示器，坐标轴触发有效
                                                    type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                                }
                                            },
                                            legend: {
                                                data: data.legend,
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
                                                    data: data.xdata,
                                                }
                                            ],
                                            yAxis: [
                                                {
                                                    type: 'value',
                                                    axisLabel: {
                                                        show: true,
                                                        interval: 'auto',
                                                    }
                                                }
                                            ],
                                            series: [
                                                {
                                                    name: data.legend,
                                                    type: 'bar',
                                                    data: data.seriesdata
                                                }
                                            ]
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
