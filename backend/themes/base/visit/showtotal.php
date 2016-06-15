<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\services\NginxService;
use yii\helpers\Url;
use yii\web\View;
$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$this->registerJsFile('/base/js/china.js', [
    'position' => View::POS_HEAD
]);
$this->registerJsFile('/base/js/world.js', [
    'position' => View::POS_HEAD
]);
$this->title = 'nginx访问统计';

$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}
//$search_date = '2016-06-06';
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
                                $userVisits = NginxService::findOneTypeAmounts($search_date, 'status', NginxService::AccessStatistic21);
                                if (!empty($userVisits)):
                                    //获得21今日总流量
                                    $userFlow = NginxService::findOneTypeAmounts($search_date, 'content_size', NginxService::AccessStatistic21);
                                    //获得21今日总耗时
                                    $userTime = NginxService::findOneTypeAmounts($search_date, 'take_time', NginxService::AccessStatistic21);
                                    ?>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td colspan="6"><h5>21代理服务器<a class="pull-right" target="_blank" href="<?= Url::toRoute('/nginx/sqlattack') . '?source=21&date=' . $search_date ?>">查看sql注入攻击</a></h5></td>
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
                                                    <div class="col-lg-12" id="main_21"  style="width: 550px;height:500px;">
                                                        <script type="text/javascript">
                                                            var myChart21 = echarts.init(document.getElementById('main_21'));
                                                            function randomData() {
                                                                return Math.round(Math.random() * 1000);
                                                            }
                                                            myChart21.showLoading();

                                                            $.get('/visit/api.html?fc=chinamap&proxy=21&date=<?= $search_date ?>', function(data) {
                                                                myChart21.hideLoading();
                                                                myChart21.setOption(option = {
                                                                    title: {
                                                                        text: data.text,
                                                                        left: 'center'
                                                                    },
                                                                    tooltip: {
                                                                        trigger: 'item'
                                                                    },
                                                                    legend: {
                                                                        orient: 'vertical',
                                                                        left: 'left',
                                                                        data: [data.dataname]
                                                                    },
                                                                    visualMap: {
                                                                        min: 0,
                                                                        max: data.maxshow,
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
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: data.dataname,
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
                                                                            data: data.series.data
                                                                        },
                                                                    ]
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="main21_world"  style="width: 550px;height:350px;">
                                                        <script type="text/javascript">
                                                            var onemyChart21world = echarts.init(document.getElementById('main21_world'));
                                                            onemyChart21world.showLoading();

                                                            $.get('/visit/api.html?fc=worldmap&proxy=21&date=<?= $search_date ?>', function(data) {
                                                                onemyChart21world.hideLoading();
                                                                onemyChart21world.setOption(option = {
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
                                                                        text: ['高', '低'],
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
                                                                            data: data.series.data
                                                                        }
                                                                    ]
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>操作平台-浏览器：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="plat_brower_main17"  style="width: 550px;height:400px;">
                                                        <script type="text/javascript">
                                                            var plat_brower_main17 = echarts.init(document.getElementById('plat_brower_main17'));
                                                            plat_brower_main17.showLoading();

                                                            $.get('/visit/api.html?fc=plat_brower&proxy=17&date=<?= $search_date ?>', function(data) {
                                                                plat_brower_main17.hideLoading();
                                                                plat_brower_main17.setOption(option = {
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
                                                                                    show: true
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
                                                                            name: '操作平台',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '30%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                                            name: '浏览器类型',
                                                                            type: 'pie',
                                                                            radius: ['35%', '65%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>移动手机-浏览器：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="mobile_brower_main17"  style="width: 550px;height:400px;">
                                                        <script type="text/javascript">
                                                            var mobile_brower_main17 = echarts.init(document.getElementById('mobile_brower_main17'));
                                                            mobile_brower_main17.showLoading();

                                                            $.get('/visit/api.html?fc=plat_brower&proxy=17&date=<?= $search_date ?>', function(data) {
                                                                mobile_brower_main17.hideLoading();
                                                                mobile_brower_main17.setOption(option = {
                                                                    tooltip: {
                                                                        trigger: 'item',
                                                                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: '浏览器',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '40%'],
                                                                            center: ['25%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: true
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
                                                                            name: '手机平台',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '30%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                                            name: '浏览器',
                                                                            type: 'pie',
                                                                            radius: ['35%', '65%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>状态：<a class="pull-right"  target="_blank"  href="<?= Url::toRoute('/nginx/errorstatus') . '?source=21&date=' . $search_date ?>">查看错误链接</a></h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="plat_status_main17"  style="width: 550px;height:400px;">
                                                        <script type="text/javascript">
                                                            var plat_status_main17 = echarts.init(document.getElementById('plat_status_main17'));
                                                            plat_status_main17.showLoading();

                                                            $.get('/visit/api.html?fc=errorstatus&proxy=21&date=<?= $search_date ?>', function(data) {
                                                                plat_status_main17.hideLoading();
                                                                plat_status_main17.setOption(option = {
                                                                    tooltip: {
                                                                        trigger: 'item',
                                                                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: '错误状态',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '40%'],
                                                                            center: ['25%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: true
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
                                                                            name: '站点',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '30%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                     position: 'inner'
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
                                                                            name: '错误状态',
                                                                            type: 'pie',
                                                                            radius: ['35%', '65%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                $userVisits = NginxService::findOneTypeAmounts($search_date, 'status', NginxService::AccessStatistic17);
                                if (!empty($userVisits)):
//获得17今日总流量
                                    $userFlow = NginxService::findOneTypeAmounts($search_date, 'content_size', NginxService::AccessStatistic17);
                                    //获得17今日总耗时
                                    $userTime = NginxService::findOneTypeAmounts($search_date, 'take_time', NginxService::AccessStatistic17);
                                    ?>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td colspan="6"><h5>17代理服务器<a class="pull-right" target="_blank" href="<?= Url::toRoute('/nginx/sqlattack') . '?source=17&date=' . $search_date ?>">查看sql注入攻击</a></h5></td>
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
                                                    <div class="col-lg-12" id="main"  style="width: 550px;height:500px;">
                                                        <script type="text/javascript">
                                                            var myChart = echarts.init(document.getElementById('main'));
                                                            function randomData() {
                                                                return Math.round(Math.random() * 1000);
                                                            }
                                                            myChart.showLoading();

                                                            $.get('/visit/api.html?fc=chinamap&proxy=17&date=<?= $search_date ?>', function(data) {
                                                                myChart.hideLoading();
                                                                myChart.setOption(option = {
                                                                    title: {
                                                                        text: data.text,
                                                                        left: 'center'
                                                                    },
                                                                    tooltip: {
                                                                        trigger: 'item'
                                                                    },
                                                                    legend: {
                                                                        orient: 'vertical',
                                                                        left: 'left',
                                                                        data: [data.dataname]
                                                                    },
                                                                    visualMap: {
                                                                        min: 0,
                                                                        max: data.maxshow,
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
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: data.dataname,
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
                                                                            data: data.series.data
                                                                        },
                                                                    ]
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="main_world"  style="width: 550px;height:350px;">
                                                        <script type="text/javascript">
                                                            var onemyChart = echarts.init(document.getElementById('main_world'));
                                                            onemyChart.showLoading();

                                                            $.get('/visit/api.html?fc=worldmap&proxy=17&date=<?= $search_date ?>', function(data) {
                                                                onemyChart.hideLoading();
                                                                onemyChart.setOption(option = {
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
                                                                        text: ['高', '低'],
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
                                                                            data: data.series.data
                                                                        }
                                                                    ]
                                                                });
                                                            });
                                                        </script>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>操作平台-浏览器：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="plat_brower_main21"  style="width: 550px;height:400px;">
                                                        <script type="text/javascript">
                                                            var plat_brower_main21 = echarts.init(document.getElementById('plat_brower_main21'));
                                                            plat_brower_main21.showLoading();

                                                            $.get('/visit/api.html?fc=plat_brower&proxy=21&date=<?= $search_date ?>', function(data) {
                                                                plat_brower_main21.hideLoading();
                                                                plat_brower_main21.setOption(option = {
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
                                                                                    show: true
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
                                                                            name: '操作平台',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '30%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                                            name: '浏览器类型',
                                                                            type: 'pie',
                                                                            radius: ['35%', '65%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>移动手机-浏览器：</h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="mobile_brower_main21"  style="width: 550px;height:400px;">
                                                        <script type="text/javascript">
                                                            var mobile_brower_main21 = echarts.init(document.getElementById('mobile_brower_main21'));
                                                            mobile_brower_main21.showLoading();

                                                            $.get('/visit/api.html?fc=plat_brower&proxy=21&date=<?= $search_date ?>', function(data) {
                                                                mobile_brower_main21.hideLoading();
                                                                mobile_brower_main21.setOption(option = {
                                                                    tooltip: {
                                                                        trigger: 'item',
                                                                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: '浏览器',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '40%'],
                                                                            center: ['25%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: true
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
                                                                            name: '手机平台',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '30%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                                            name: '浏览器',
                                                                            type: 'pie',
                                                                            radius: ['35%', '65%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
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
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $search_date ?>状态：<a class="pull-right" target="_blank" href="<?= Url::toRoute('/nginx/errorstatus') . '?table=17&date=' . $search_date ?>">查看错误链接</a></h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="col-lg-12" id="plat_status_main21"  style="width: 550px;height:400px;">
                                                        <script type="text/javascript">
                                                            var plat_status_main21 = echarts.init(document.getElementById('plat_status_main21'));
                                                            plat_status_main21.showLoading();

                                                            $.get('/visit/api.html?fc=errorstatus&proxy=21&date=<?= $search_date ?>', function(data) {
                                                                plat_status_main21.hideLoading();
                                                                plat_status_main21.setOption(option = {
                                                                    tooltip: {
                                                                        trigger: 'item',
                                                                        formatter: "{a} <br/>{b}: {c} ({d}%)"
                                                                    },
                                                                    series: [
                                                                        {
                                                                            name: '错误状态',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '40%'],
                                                                            center: ['25%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: true
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
                                                                            name: '站点',
                                                                            type: 'pie',
                                                                            selectedMode: 'single',
                                                                            radius: [0, '30%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    position: 'inner'
                                                                                }
                                                                            },
                                                                            labelLine: {
                                                                                normal: {
                                                                                    show: true
                                                                                }
                                                                            },
                                                                            data: data.platdata
                                                                        },
                                                                        {
                                                                            name: '错误状态',
                                                                            type: 'pie',
                                                                            radius: ['35%', '65%'],
                                                                            center: ['75%', '50%'],
                                                                            label: {
                                                                                normal: {
                                                                                    show: false
                                                                                }
                                                                            },
                                                                            labelLine: {
                                                                                normal: {
                                                                                    show: true
                                                                                }
                                                                            },
                                                                            data: data.brower
                                                                        }
                                                                    ]
                                                                })
                                                            });
                                                        </script>
                                                    </div>
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
