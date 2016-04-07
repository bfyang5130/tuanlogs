<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use miloschuman\highcharts\Highcharts;
use backend\services\ZabbixHightchartService;
use yii\helpers\Url;
use yii\bootstrap\Modal;

$this->title = '监控查询';

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
                        //获得要选择的数据
                        List($id, $stime, $etime) = ZabbixHightchartService::getSelectId();
                        //通过接口获得图形显示的信息
                        List($showLists5,$isindex) = ZabbixHightchartService::findSelectColumn($id, $stime, $etime);
                        if (!empty($showLists5)):
                            ?>
                            <table class="table table-bordered table-striped table-condensed">
                                <tbody>
                                    <tr>
                                        <td><h5><?= $showLists5['texttitle'] ?><span style="margin-left: 10px;" class="pull-right"><?= $showLists5['server'] ?></span>

                                                <?=
                                                Modal::widget([
                                                    'id' => 'contact-modal',
                                                    'toggleButton' => [
                                                        'label' => '更改首页显示',
                                                        'tag' => 'a',
                                                        'class' => 'pull-right',
                                                        'data-target' => '#contact-modal',
                                                        'href' => Url::toRoute('/server/setindex') . '?id=' . $id.'&isindex='.$isindex,
                                                    ],
                                                    'clientOptions' => false,
                                                ]);
                                                ?>
                                            </h5></td>
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
                                                        'text' => $showLists5['texttitle']
                                                    ],
                                                    'xAxis' => [
                                                        'categories' => $showLists5['categories'],
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
                                                    'series' => [$showLists5['series']]
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
                                        <td><h5>当前查询没有数据</h5></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            所选择的查询没有配置正确
                                        </td>
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
