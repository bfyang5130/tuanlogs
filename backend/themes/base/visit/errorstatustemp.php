<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\web\View;

$this->registerJsFile('/base/js/echarts-all-3.js', [
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

                                    $.get('/visit/api.html?fc=errorstatus&proxy=17&date=2016-06-06', function(data) {
                                        myChart.hideLoading();
                                        myChart.setOption(option = {
                                            tooltip: {
                                                trigger: 'item',
                                                formatter: "{a} <br/>{b}: {c} ({d}%)"
                                            },
                                            
                                            series: [
                                                {
                                                    name: '访问来源',
                                                    type: 'pie',
                                                    selectedMode: 'single',
                                                    radius: [0, '40%'],
                                                    center: ['25%', '50%'],
                                                    label: {
                                                        normal: {
                                                            show:true
                                                        }
                                                    },
                                                    labelLine: {
                                                        normal: {
                                                            show: true
                                                        }
                                                    },
                                                    data: data.alonebrower
                                                },
                                                {
                                                    name: '访问来源',
                                                    type: 'pie',
                                                    selectedMode: 'single',
                                                    radius: [0, '40%'],
                                                    center: ['75%', '50%'],
                                                    label: {
                                                        normal: {
                                                            show:false
                                                        }
                                                    },
                                                    labelLine: {
                                                        normal: {
                                                            show: false
                                                        }
                                                    },
                                                    data: data.platdata
                                                },
                                                {
                                                    name: '访问来源',
                                                    type: 'pie',
                                                    radius: ['50%', '75%'],
                                                    center: ['75%', '50%'],
                                                    label: {
                                                        normal: {
                                                            show:false
                                                        }
                                                    },
                                                    labelLine: {
                                                        normal: {
                                                            show: false
                                                        }
                                                    },
                                                    data: data.brower
                                                }
                                            ]
                                        })
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
