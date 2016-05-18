<?php

use yii\widgets\Breadcrumbs;
use backend\services\ErrorHightchartService;
use miloschuman\highcharts\Highcharts;
use backend\services\SqlHightchartService;
use backend\services\ZabbixHightchartService;
use backend\services\NginxHightchartService;
use yii\helpers\Url;
use yii\web\View;

$this->title = '服务器数据总览';
$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
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
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">

                        </div>
                        <div class="row">

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
                                                    <td>
                                                        <div class="panel-body">
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="main1"><?= $oneShowItem->id ?>
                                                                    <script type="text/javascript">
                                                                            $.get('/server/api.html?fc=twodayfit&monitor_id=<?= $oneShowItem->id ?>&date=<?= date("Y-m-d") ?>', function(data) {
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

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>