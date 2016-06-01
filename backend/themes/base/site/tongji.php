<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\View;

$this->title = '基本统计信息';
$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$starttime = date('Y-m-01 00:00:00');
$endtime = date('Y-m-01 00:00:00', strtotime('+1 month', strtotime($starttime)));
?>
<div class="site-index">
    <?php
    echo Breadcrumbs::widget([
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => [
            [
                'label' => '基本统计信息'
            ],
        ],
    ]);
    ?>

    <div class="body-content">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                #获得所有错误类型最近五天的趋势
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><h5>程序统计<a class="pull-right" target="_blank" href="<?= Url::toRoute('/errors/index') ?>">查看错误日志</a></h5></td>
                                        </tr>
                                        <tr>
                                            <td width="50%" >
                                                <div id="mainerr" style="width:605px;height:350px">
                                                    <script type="text/javascript">
                                                        // 基于准备好的dom，初始化echarts实例
                                                        var myChartErr = echarts.init(document.getElementById('mainerr'));
                                                        myChartErr.showLoading();

                                                        $.get('/errors/api.html?fc=fivecolumn', function(data) {
                                                            myChartErr.hideLoading();
                                                            myChartErr.setOption({
                                                                title: {
                                                                    text: '最多错误的五个应用',
                                                                    y: 'bottom',
                                                                    left: 'center'
                                                                },
                                                                tooltip: {
                                                                    trigger: 'axis',
                                                                    axisPointer: {// 坐标轴指示器，坐标轴触发有效
                                                                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                                                    }
                                                                },
                                                                legend: {
                                                                    data: [data.series.name],
                                                                    show: true
                                                                },
                                                                xAxis: [
                                                                    {
                                                                        type: 'category',
                                                                        data: data.categories,
                                                                    }
                                                                ],
                                                                yAxis: {},
                                                                series: [data.series]
                                                            });
                                                        });

                                                        myChartErr.on('click', function(parmas) {
                                                            //这里的parmas.name是一个时间数值，可以用来处理对应的时间段数据
                                                            //获得详细指定的时间段数据，并更改图形内的数据
                                                            window.location.href = "<?= Url::toRoute('/errors/index') . '?ErrorLogSearch%5Bstart_date%5D=' . $starttime . '&ErrorLogSearch%5Bend_date%5D=' . $endtime . '&ErrorLogSearch%5BApplicationId%5D=' ?>" + parmas.name;
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="mainerrtrend" style="width:605px;height:350px">
                                                    <script type="text/javascript">
                                                        // 基于准备好的dom，初始化echarts实例
                                                        var myChartErrTrend = echarts.init(document.getElementById('mainerrtrend'));
                                                        myChartErrTrend.showLoading();

                                                        $.get('/errors/api.html?fc=findAllLine', function(data) {
                                                            myChartErrTrend.hideLoading();
                                                            myChartErrTrend.setOption({
                                                                title: {
                                                                    text: '最近五天错误趋势图',
                                                                    y: 'bottom',
                                                                    left: 'center'
                                                                },
                                                                tooltip: {
                                                                    trigger: 'axis'
                                                                },
                                                                legend: {
                                                                    data: data.toptip,
                                                                    itemHeight: 9,
                                                                },
                                                                xAxis: {
                                                                    type: 'category',
                                                                    boundaryGap: false,
                                                                    data: data.categories
                                                                },
                                                                yAxis: {
                                                                    type: 'value'
                                                                },
                                                                series: data.series
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                #获得所有错误类型最近五天的趋势
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><h5>SQL统计<a class="pull-right" target="_blank" href="<?= Url::toRoute('/sql/longtimesql') ?>">查看慢日志</a></h5></td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                <div id="mainsql" style="width:605px;height:350px">
                                                    <script type="text/javascript">
                                                        // 基于准备好的dom，初始化echarts实例
                                                        var myChartSql = echarts.init(document.getElementById('mainsql'));
                                                        myChartSql.showLoading();

                                                        $.get('/sql/api.html?fc=fivecolumn', function(data) {
                                                            myChartSql.hideLoading();
                                                            myChartSql.setOption({
                                                                title: {
                                                                    text: '起过800毫秒的查询',
                                                                    y: 'bottom',
                                                                    left: 'center'
                                                                },
                                                                tooltip: {
                                                                    trigger: 'axis',
                                                                    axisPointer: {// 坐标轴指示器，坐标轴触发有效
                                                                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                                                    }
                                                                },
                                                                legend: {
                                                                    data: [data.series.name],
                                                                    show: true
                                                                },
                                                                xAxis: [
                                                                    {
                                                                        type: 'category',
                                                                        data: data.categories,
                                                                    }
                                                                ],
                                                                yAxis: {},
                                                                series: [data.series]
                                                            });
                                                        });

                                                        myChartSql.on('click', function(parmas) {
                                                            //这里的parmas.name是一个时间数值，可以用来处理对应的时间段数据
                                                            //获得详细指定的时间段数据，并更改图形内的数据
                                                            window.location.href = "<?= Url::toRoute('/sql/longtimesql') . '??LongtimesqlSearch%5Bdatabasetype%5D=' ?>" + parmas.name;
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="mainsqltrend" style="width:605px;height:350px">
                                                    <script type="text/javascript">
                                                        // 基于准备好的dom，初始化echarts实例
                                                        var myChartSqlTrend = echarts.init(document.getElementById('mainsqltrend'));
                                                        myChartSqlTrend.showLoading();

                                                        $.get('/sql/api.html?fc=findAllLine', function(data) {
                                                            myChartSqlTrend.hideLoading();
                                                            myChartSqlTrend.setOption({
                                                                title: {
                                                                    text: '最近五天慢日志趋势图',
                                                                    y: 'bottom',
                                                                    left: 'center'
                                                                },
                                                                tooltip: {
                                                                    trigger: 'axis'
                                                                },
                                                                legend: {
                                                                    data: data.toptip,
                                                                },
                                                                xAxis: {
                                                                    type: 'category',
                                                                    boundaryGap: false,
                                                                    data: data.categories
                                                                },
                                                                yAxis: {
                                                                    type: 'value'
                                                                },
                                                                series: data.series
                                                            });
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>服务器性能监控<a class="pull-right" target="_blank" href="<?= Url::toRoute('/server/index') ?>">查看更多监控选项</a></h5></td>
                                        </tr>
                                        <?php
                                        //循环取得三个推荐服务器性能显示
                                        ?>
                                        <?php
                                        $showLists5 = \backend\services\ServerStatusService::find5Column();
                                        if (!empty($showLists5)):
                                            $showI = 1;
                                            foreach ($showLists5 as $oneShowItem):
                                                ?>
                                                <tr>
                                                    <td><span class="pull-left"><?= $oneShowItem->monitor_name ?></span><span class="pull-right"><?= $oneShowItem->monitor_host ?></span></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="panel-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="main<?= $showI ?>" style="height:250px;">
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function() {


                                                                            var <?= "myChart" . $showI ?> = echarts.init(document.getElementById('main<?= $showI ?>'));
        <?= "myChart" . $showI ?>.showLoading();

                                                                            $.get('/server/api.html?fc=twodayfit&monitor_id=<?= $oneShowItem->id ?>&date=<?= date("Y-m-d") ?>', function(data) {

                                                                                if (data.legenddata == "undefined") {
                                                                                    return;
                                                                                }
        <?= "myChart" . $showI ?>.hideLoading();
        <?= "myChart" . $showI ?>.setOption(option = {
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
                                                                                            max: data.max,
                                                                                            axisLabel: {
                                                                                                show: true,
                                                                                                interval: 'auto',
                                                                                                formatter: data.format,
                                                                                            }
                                                                                        }
                                                                                    ],
                                                                                    series: data.series
                                                                                });
                                                                            });
        <?= "myChart" . $showI ?>.on('click', function(parmas) {
                                                                                //这里的parmas.name是一个时间数值，可以用来处理对应的时间段数据
                                                                                //获得详细指定的时间段数据，并更改图形内的数据
                                                                                var fitnametime = parmas.name;
                                                                                if (fitnametime.length > 5) {
                                                                                    //返回直接跳转回当值页面就算了
                                                                                    if (confirm("确定返回上层数据吗？")) {
                                                                                        location.reload();
                                                                                    }
                                                                                } else {
                                                                                    alert("数据加载中...请稍侯");
                                                                                    $.get('/server/api.html?fc=detail&monitor_id=<?= $oneShowItem->id ?>&date=<?= date("Y-m-d") ?>&detialtime=' + parmas.name, function(data) {

                                                                                        if (data.seriesdata1 == "undefined") {
                                                                                            alert("无法获得这个时间点的数据");
                                                                                        } else {
                                                                                            var newoption = <?= "myChart" . $showI ?>.getOption();
                                                                                            newoption.series[0].data = data.seriesdata1;
                                                                                            newoption.series[1].data = data.seriesdata2;
                                                                                            newoption.xAxis[0].data = data.xAxisdata;
                                                                                            newoption.dataZoom = data.dataZoom;
        <?= "myChart" . $showI ?>.setOption(newoption);
                                                                                        }
                                                                                    });
                                                                                }
                                                                            });
                                                                        });
                                                                    </script>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php
                                                $showI++;
                                            endforeach;
                                        endif;
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php
                                #获得所有错误类型最近五天的趋势
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="2"><h5>页面统计信息<a class="pull-right" target="_blank" href="<?= Url::toRoute('/nginx/errorstatus') ?>">查看更多信息</a></h5></td>
                                        </tr>
                                        <tr>
                                            <td width="50%">
                                                <div id="mainngix" style="width:605px;height:350px">
                                                    <script type="text/javascript">
                                                        // 基于准备好的dom，初始化echarts实例
                                                        var myChartNgix = echarts.init(document.getElementById('mainngix'));
                                                        myChartNgix.showLoading();

                                                        $.get('/nginx/api.html?fc=pageattack', function(data) {
                                                            myChartNgix.hideLoading();
                                                            myChartNgix.setOption({
                                                                title: {
                                                                    text: '错误访问状态统计',
                                                                    y: 'bottom',
                                                                    left: 'center'
                                                                },
                                                                tooltip: {
                                                                    trigger: 'axis',
                                                                    axisPointer: {// 坐标轴指示器，坐标轴触发有效
                                                                        type: 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                                                                    }
                                                                },
                                                                legend: {
                                                                    data: [data.series.name],
                                                                    show: true
                                                                },
                                                                xAxis: [
                                                                    {
                                                                        type: 'category',
                                                                        data: data.categories,
                                                                    }
                                                                ],
                                                                yAxis: {},
                                                                series: [data.series]
                                                            });
                                                        });

                                                        myChartNgix.on('click', function(parmas) {
                                                            //这里的parmas.name是一个时间数值，可以用来处理对应的时间段数据
                                                            //获得详细指定的时间段数据，并更改图形内的数据
                                                            if (parmas.name == "注入") {
                                                                window.location.href = "<?= Url::toRoute('/nginx/sqlattack') ?>";
                                                            } else {
                                                                window.location.href = "<?= Url::toRoute('/nginx/errorstatus') . '?AccessLogErrorStatusSearch%5Berror_status%5D=' ?>" + parmas.name;
                                                            }
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="mainngixtrend" style="width:605px;height:350px">
                                                    <script type="text/javascript">
                                                        // 基于准备好的dom，初始化echarts实例
                                                        var myChartNgixTrend = echarts.init(document.getElementById('mainngixtrend'));
                                                        myChartNgixTrend.showLoading();

                                                        $.get('/nginx/api.html?fc=findAllLine', function(data) {
                                                            myChartNgixTrend.hideLoading();
                                                            myChartNgixTrend.setOption({
                                                                title: {
                                                                    text: '最近五天错误趋势图',
                                                                    y: 'bottom',
                                                                    left: 'center'
                                                                },
                                                                tooltip: {
                                                                    trigger: 'axis'
                                                                },
                                                                legend: {
                                                                    data: data.toptip,
                                                                },
                                                                xAxis: {
                                                                    type: 'category',
                                                                    boundaryGap: false,
                                                                    data: data.categories
                                                                },
                                                                yAxis: {
                                                                    type: 'value'
                                                                },
                                                                series: data.series
                                                            });
                                                        });
                                                    </script>
                                                </div>
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