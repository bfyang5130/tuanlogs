<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\web\View;

$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$this->registerJsFile('/base/js/china.js', [
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
                                    function randomData() {
                                        return Math.round(Math.random() * 1000);
                                    }
                                    myChart.showLoading();

                                    $.get('/server/api.html?fc=twodayfit&monitor_id=1&date=2016-05-18', function(data) {
                                        myChart.hideLoading();
                                        myChart.setOption(option = {
                                            title: {
                                                text: '今日全国各地访问情况',
                                                subtext: '纯属虚构',
                                                left: 'center'
                                            },
                                            tooltip: {
                                                trigger: 'item'
                                            },
                                            legend: {
                                                orient: 'vertical',
                                                left: 'left',
                                                data: ['iphone3', 'iphone4', 'iphone5']
                                            },
                                            visualMap: {
                                                min: 0,
                                                max: 2500,
                                                left: 'left',
                                                top: 'bottom',
                                                text: ['高', '低'], // 文本，默认为数值文本
                                                calculable: true
                                            },
                                            toolbox: {
                                                show: true,
                                                orient: 'vertical',
                                                left: 'right',
                                                top: 'center',
                                                feature: {
                                                    dataView: {readOnly: false},
                                                    restore: {},
                                                    saveAsImage: {}
                                                }
                                            },
                                            series: [
                                                {
                                                    name: 'iphone3',
                                                    type: 'map',
                                                    mapType: 'china',
                                                    roam: false,
                                                    label: {
                                                        normal: {
                                                            show: true
                                                        },
                                                        emphasis: {
                                                            show: true
                                                        }
                                                    },
                                                    data: [
                                                        {name: '北京', value: randomData()},
                                                        {name: '天津', value: randomData()},
                                                        {name: '上海', value: randomData()},
                                                        {name: '重庆', value: randomData()},
                                                        {name: '河北', value: randomData()},
                                                        {name: '河南', value: randomData()},
                                                        {name: '云南', value: randomData()},
                                                        {name: '辽宁', value: randomData()},
                                                        {name: '黑龙江', value: randomData()},
                                                        {name: '湖南', value: randomData()},
                                                        {name: '安徽', value: randomData()},
                                                        {name: '山东', value: randomData()},
                                                        {name: '新疆', value: randomData()},
                                                        {name: '江苏', value: randomData()},
                                                        {name: '浙江', value: randomData()},
                                                        {name: '江西', value: randomData()},
                                                        {name: '湖北', value: randomData()},
                                                        {name: '广西', value: randomData()},
                                                        {name: '甘肃', value: randomData()},
                                                        {name: '山西', value: randomData()},
                                                        {name: '内蒙古', value: randomData()},
                                                        {name: '陕西', value: randomData()},
                                                        {name: '吉林', value: randomData()},
                                                        {name: '福建', value: randomData()},
                                                        {name: '贵州', value: randomData()},
                                                        {name: '广东', value: randomData()},
                                                        {name: '青海', value: randomData()},
                                                        {name: '西藏', value: randomData()},
                                                        {name: '四川', value: randomData()},
                                                        {name: '宁夏', value: randomData()},
                                                        {name: '海南', value: randomData()},
                                                        {name: '台湾', value: randomData()},
                                                        {name: '香港', value: randomData()},
                                                        {name: '澳门', value: randomData()}
                                                    ]
                                                },
                                                {
                                                    name: 'iphone4',
                                                    type: 'map',
                                                    mapType: 'china',
                                                    label: {
                                                        normal: {
                                                            show: true
                                                        },
                                                        emphasis: {
                                                            show: true
                                                        }
                                                    },
                                                    data: [
                                                        {name: '北京', value: randomData()},
                                                        {name: '天津', value: randomData()},
                                                        {name: '上海', value: randomData()},
                                                        {name: '重庆', value: randomData()},
                                                        {name: '河北', value: randomData()},
                                                        {name: '安徽', value: randomData()},
                                                        {name: '新疆', value: randomData()},
                                                        {name: '浙江', value: randomData()},
                                                        {name: '江西', value: randomData()},
                                                        {name: '山西', value: randomData()},
                                                        {name: '内蒙古', value: randomData()},
                                                        {name: '吉林', value: randomData()},
                                                        {name: '福建', value: randomData()},
                                                        {name: '广东', value: randomData()},
                                                        {name: '西藏', value: randomData()},
                                                        {name: '四川', value: randomData()},
                                                        {name: '宁夏', value: randomData()},
                                                        {name: '香港', value: randomData()},
                                                        {name: '澳门', value: randomData()}
                                                    ]
                                                },
                                                {
                                                    name: 'iphone5',
                                                    type: 'map',
                                                    mapType: 'china',
                                                    label: {
                                                        normal: {
                                                            show: true
                                                        },
                                                        emphasis: {
                                                            show: true
                                                        }
                                                    },
                                                    data: [
                                                        {name: '北京', value: randomData()},
                                                        {name: '天津', value: randomData()},
                                                        {name: '上海', value: randomData()},
                                                        {name: '广东', value: randomData()},
                                                        {name: '台湾', value: randomData()},
                                                        {name: '香港', value: randomData()},
                                                        {name: '澳门', value: randomData()}
                                                    ]
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
