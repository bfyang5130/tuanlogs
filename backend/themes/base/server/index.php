<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\models\MonitorSearch;
use yii\widgets\LinkPager;
use yii\helpers\Url;
use yii\web\View;

$this->title = '服务器监控列表';
$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}


$params = \Yii::$app->request->get();
//处理时间
$accLogErr = new MonitorSearch();

$thisDayErrorsLists = $accLogErr->search($params);
$pager = $thisDayErrorsLists->getPagination();
$datas = $thisDayErrorsLists->getModels();
$begin = $pager->page * $pager->pageSize + 1;
$end = $begin + $pager->pageSize - 1;
if ($begin > $end) {
    $begin = $end;
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
                                <div class="summary">第<b><?= $begin . '-' . $end ?></b>条，共<b><?= $pager->totalCount ?></b>条数据.</div>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <?php
                                        $showI = 1;
                                        ?>
                                        <?php foreach ($datas as $oneErrorValue): ?>
                                            <tr>
                                                <td><span class="pull-left"><?= $oneErrorValue['monitor_name'] ?></span><a class="pull-right" href="<?= Url::toRoute('/server/selectmonitor').'?monitor_id='.$oneErrorValue['id'].'&date='.date('Y-m-d') ?>">更多详情</a><span class="pull-right"><?= $oneErrorValue['monitor_host'] ?>&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
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

                                                                        $.get('/server/api.html?fc=twodayfit&monitor_id=<?= $oneErrorValue['id'] ?>&date=<?= date("Y-m-d") ?>', function(data) {
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
                                                                                $.get('/server/api.html?fc=detail&monitor_id=<?= $oneErrorValue['id'] ?>&date=<?= date("Y-m-d") ?>&detialtime=' + parmas.name, function(data) {

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
                                        ?>
                                        <tr>
                                            <td class="text-center">
                                                <?= LinkPager::widget(['pagination' => $pager]); ?>
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
