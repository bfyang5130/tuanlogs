<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\web\View;

$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$this->registerJsFile('/base/js/world.js', [
    'position' => View::POS_HEAD
]);
$this->title = '监制信息处理';
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
                            <div class="col-lg-12" id="main"  style="width: 1240px;height:650px;">
                                <script type="text/javascript">
                                    var myChart = echarts.init(document.getElementById('main'));
                                    myChart.showLoading();

                                    $.get('/visit/api.html?fc=worldmap&proxy=17&date=2016-02-26', function(data) {
                                        myChart.hideLoading();
                                        myChart.setOption(option = {
                                            title: {
                                                text: data.text,
                                                left: 'center',
                                                top: 'top'
                                            },
                                            tooltip: {
                                                trigger: 'item',
                                            },
                                            toolbox: {
                                                show: true,
                                                orient: 'vertical',
                                                left: 'right',
                                                top: 'center',
                                            },
                                            visualMap: {
                                                min: 0,
                                                max: data.maxshow,
                                                text: ['High', 'Low'],
                                                realtime: false,
                                                calculable: true,
                                                color: ['orangered', 'yellow', 'lightskyblue']
                                            },
                                            series: [
                                                {
                                                    name: data.dataname,
                                                    type: 'map',
                                                    mapType: 'world',
                                                    roam: true,
                                                    itemStyle: {
                                                        emphasis: {label: {show: true}}
                                                    },
                                                    data:data.series.data
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
