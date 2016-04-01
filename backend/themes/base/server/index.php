<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use miloschuman\highcharts\Highcharts;
use backend\services\ZabbixHightchartService;

$this->title = '服务器监控列表';

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
                        <?php
                        //默认获得5个监控荐项进行近三个钟头的展示（这里可扩展为选择项）
                        $showLists5 = ZabbixHightchartService::find5Column();
                        if (!empty($showLists5)):
                            foreach ($showLists5 as $value):
                                if (!isset($value['status'])):
                                    ?>
                                    <table class="table table-bordered table-striped table-condensed">
                                        <tbody>
                                            <tr>
                                                <td><h5><?= $value['texttitle'] ?><span class="pull-right"><?= $value['server'] ?></span></h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?=
                                                    Highcharts::widget([
                                                        'options' => [
                                                            'chart' => [
                                                                'type' => 'column',
                                                                'plotShadow' => true, //设置阴影
                                                                'height' => 350,
                                                            ],
                                                            'title' => [
                                                                'text' => $value['texttitle']
                                                            ],
                                                            'xAxis' => [
                                                                'categories' => $value['categories'],
                                                            ],
                                                            'yAxis' => [
                                                                'min' => 0,
                                                                'stackLabels' => [
                                                                    'enabled' => true,
                                                                ]
                                                            ],
                                                            'credits' => [
                                                                'enabled' => false//不显示highCharts版权信息
                                                            ],
                                                            'plotOptions' => [
                                                                'column' => [
                                                                    'stacking' => 'normal',
                                                                    'dataLabels' => [
                                                                        'enabled' => FALSE,
                                                                        'color' => 'black',
                                                                    ],
                                                                ],
                                                            ],
                                                            'legend' => [
                                                                'verticalAlign' => "top",
                                                                'floating' => true,
                                                                'y' => 20,
                                                            ],
                                                            'series' => [$value['series']]
                                                        ]
                                                    ]);
                                                    ?>
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
                                                <td><h5><?= $value['texttitle'] ?><span class="pull-right"><?= $value['server'] ?></span></h5></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <?= $value['error'] ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <?php
                                endif;
                            endforeach;
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
