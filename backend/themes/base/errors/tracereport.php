<?php

/**
 * Created by PhpStorm.
 * User: haohui
 * Date: 2016/2/16
 * Time: 15:19
 */
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use nirvana\showloading\ShowLoadingAsset;
use miloschuman\highcharts\Highcharts;
use yii\widgets\Pjax;


ShowLoadingAsset::register($this);

$this->title = '跟踪日志报表';
$params = \Yii::$app->request->queryParams;
$page = empty(Yii::$app->request->get('page')) ? '1' : intval(Yii::$app->request->get('page'));
$years = \Yii::$app->request->get('years', '');
?>
<?php Pjax::begin(['id'=>'grid'])?>
<div class="site-index">
    <?php
    echo Breadcrumbs::widget([
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => [
            [
                'label' => $this->title
            ],
        ],
    ]);
    ?>
    <div class="body-content" id="text_body_c">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <!--饼形图-->
                <?php if (empty($type)) { ?>
                    <div class="panel-body">
                        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                            <div class="btn-group pull-left" role="group" aria-label="First group">
                                <a href="<?= Url::toRoute(['/errors/tracereport']) ?>" class="btn btn-default">总统计</a>
                                <a href="<?= Url::toRoute(['/errors/tracedayreport']) ?>" class="btn btn-default">日统计</a>
                                <a href="<?= Url::toRoute(['/errors/tracemonreport']) ?>" class="btn btn-default">月统计</a>
                            </div>

                            <div class="btn-group pull-right" role="group" aria-label="First group">
                                <a href="<?= Url::toRoute('/errors/index') ?>" class="btn btn-default">列表</a>
                                <a href="<?= Url::toRoute('/errors/errorgraph') ?>" class="btn btn-default">图形</a>
                            </div>
                        </div>
                        <div class="qys_total_show">
                            <?php
                            echo Highcharts::widget([
                                'options' => [
                                    'chart' => [
                                        'type' => 'bar',
                                        'plotShadow' => false, //设置阴影
                                        'height' => 2500,
                                    ],
                                    'title' => [
                                        'text' => ' 统计'
                                    ],
                                    'credits' => [
                                        'enabled' => false//不显示highCharts版权信息
                                    ],
                                    'xAxis' => [
                                        'categories' => $category,
                                        'title' => array('text' => null),
                                    ],
                                    'yAxis' => [
                                        'min' => 0,
                                        'title' => array('text' => ''),
                                        'align' => 'high',
                                        'labels' => array("overflow" => "justify")
                                    ],
                                    'plotOptions' => [
                                        'bar' => [
                                            'dataLabels' => [
                                                'enabled' => true
                                            ]
                                        ],
                                    ],
                                    'legend' => [
                                        'layout' => 'vertical',
                                        'align' => 'right',
                                        'verticalAlign' => 'top',
                                        'x' => -40,
                                        'y' => 100,
                                        'floating' => true,
                                        'borderWidth' => 1,
                                        'backgroundColor' => '#FFFFFF',
                                        'shadow' => true,
                                    ],
                                    'tooltip' => [
                                        'enabled' => false,
                                    ],
                                    'legend' => [
                                        'verticalAlign' => "bottom",
                                    ],
                                    'series' => $series
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                <?php } ?>


                <?php if ($type == 'day') { ?>
                    <div class="form-inline text-center" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group pull-left" role="group" aria-label="First group">
                            <a href="<?= Url::toRoute(['/errors/tracereport']) ?>" class="btn btn-default">总统计</a>
                            <a href="<?= Url::toRoute(['/errors/tracedayreport']) ?>" class="btn btn-default">日统计</a>
                            <a href="<?= Url::toRoute(['/errors/tracemonreport']) ?>" class="btn btn-default">月统计</a>
                        </div>
                        <div class="form-group ">
                            <label for="exampleInputEmail2">时间:</label>
                            <?=
                            \yii\jui\DatePicker::widget([
                                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                                'attribute' => 'start_date',
                                'language' => 'zh-CN',
                                'dateFormat' => 'yyyy-MM-dd',
                                'value' => empty($search_date) ? date('Y-m-d') : $search_date,
                                'clientOptions' => [
                                    'minDate' => '2015-01-01',
                                    'maxDate' => date("Y-m-d"),
                                    'onSelect' => new \yii\web\JsExpression(
                                        "function (dateText, inst) {
                                            var url = '/errors/tracedayreport.html?search_date='+ dateText;
                                            location.href = url;
                                        }"
                                    ),
                                ],
                            ]);
                            ?>
                        </div>

                        <div class="btn-group pull-right" role="group " aria-label="First group">
                            <?php if ($page > 1) { ?>
                                <a href="<?= Url::toRoute(['/errors/tracedayreport', "page" => $page - 1]) ?>"
                                   class="btn btn-default">上一页</a>
                            <?php } ?>
                            <a href="<?= Url::toRoute(['/errors/tracedayreport', "page" => $page + 1]) ?>"
                               class="btn btn-default">下一页</a>
                        </div>
                    </div>
                    <div class="qys_total_show">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'chart' => [
                                    'type' => 'bar',
                                    'height' => 3200,
                                ],
                                'title' => [
                                    'text' => $this->title
                                ],
                                'subtitle' => [
                                    'text' => $highcharts_title
                                ],
                                'xAxis' => [
                                    'categories' => $category, //填写类别
                                    'title' => [
                                        'text' => ''
                                    ]
                                ],
                                'yAxis' => [
                                    'min' => 0,
                                    'title' => [
                                        'text' => 'Population (millions)',
                                        'align' => 'high'
                                    ],
                                    'align' => 'high',
                                    'labels' => [
                                        'overflow' => 'justify'
                                    ]
                                ],
                                'tooltip' => [
                                    'valueSuffix' => ''//数量单位
                                ],
                                'plotOptions' => [
                                    'bar' => [
                                        'dataLabels' => [
                                            'enabled' => true
                                        ]
                                    ]
                                ],
                                'legend' => [
                                    'layout' => 'horizontal',
                                    'align' => 'center',
                                    'verticalAlign' => 'top',
                                    'floating' => true,
//                                'x'=>90,
                                    'y' => 20,
                                    'borderWidth' => 1,
                                    'backgroundColor' => '#FFFFFF',
                                    'shadow' => true,
                                ],
                                'credits' => [
                                    'enabled' => false
                                ],
//                                'lang' =>[
//                                    'noData'=>'Nichts zu anzeigen'
//                                ],
//                                'noData'=>[
//                                    'style'=>[
//                                    'fontWeight'=>'bold',
//                                    'fontSize'=>'20px',
//                                    'color'=>'#303030',
//                                    ]
//                                ],
                                'series' => $trace_series
                            ]
                        ]);
                        ?>
                    </div>
                <?php } ?>
                <?php if ($type == 'month') { ?>
                    <div class="btn-toolbar pull-letf" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group pull-left" role="group" aria-label="First group">
                            <a href="<?= Url::toRoute(['/errors/tracereport']) ?>" class="btn btn-default">总统计</a>
                            <a href="<?= Url::toRoute(['/errors/tracedayreport']) ?>" class="btn btn-default">日统计</a>
                            <a href="<?= Url::toRoute(['/errors/tracemonreport']) ?>" class="btn btn-default">月统计</a>
                        </div>
                        <div class="btn-group pull-right" role="group" aria-label="First group">
                            <select id="year_select"
                                    onchange="window.location.href = '<?= url::toRoute("errors/tracemonreport") ?>?years=' + options[selectedIndex].value"
                                    class="form-control" name="years">
                                <?php foreach ($options as $key => $value) { ?>
                                    <option value="<?= $key ?>"
                                            <?php if ($years == $key) { ?>selected <?php } ?> ><?= $value ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="btn-group pull-right" role="group" aria-label="First group">
                            <!--                            <a href="/errors/tracereport.html" class="btn btn-default">返回</a>-->
                        </div>
                    </div>
                    <div class="qys_total_show">
                        <?=
                        Highcharts::widget([
                            'options' => [
                                'chart' => [
                                    'type' => 'bar',
                                    'height' => 2800,
                                ],
                                'title' => [
                                    'text' => $this->title
                                ],
                                'subtitle' => [
                                    'text' => ''
                                ],
                                'xAxis' => [
                                    'categories' => $category, //填写类别
                                    'title' => [
                                        'text' => ''
                                    ]
                                ],
                                'yAxis' => [
                                    'min' => 0,
                                    'title' => [
                                        'text' => 'Population (millions)',
                                        'align' => 'high'
                                    ],
                                    'align' => 'high',
                                    'labels' => [
                                        'overflow' => 'justify'
                                    ]
                                ],
                                'tooltip' => [
                                    'valueSuffix' => ''//数量单位
                                ],
                                'plotOptions' => [
                                    'bar' => [
                                        'dataLabels' => [
                                            'enabled' => true
                                        ]
                                    ]
                                ],
                                'legend' => [
                                    'layout' => 'horizontal',
                                    'align' => 'center',
                                    'verticalAlign' => 'top',
                                    'floating' => true,
//                                'x'=>90,
                                    'y' => 20,
                                    'borderWidth' => 1,
                                    'backgroundColor' => '#FFFFFF',
                                    'shadow' => true,
                                ],
                                'credits' => [
                                    'enabled' => false
                                ],
                                'series' => $trace_series
                            ]
                        ]);
                        ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</div>
<?php Pjax::end()?>

