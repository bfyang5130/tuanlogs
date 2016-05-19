<?php

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\web\View;

$this->title = '首页';
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
                'label' => '首页'
            ],
        ],
    ]);
    ?>

    <div class="body-content">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active" style="text-align: center;">
                        <a href="<?= Url::toRoute('/site/tongji') ?>">查看基本统计信息</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>