<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
$search_date = Yii::$app->request->get("search_date");
if (empty($url)) {
    $toUrl = '/visit/showtatal';
}
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/visit/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">NGINX访问IP统计</a>
            <a href="<?= Url::toRoute('/visit/nginxblock') ?>" class="btn btn-default<?= ($id == 'nginxblock') ? ' btn-primary' : '' ?>">Nginx面板</a>
            <a href="<?= Url::toRoute('/visit/nginxlist') ?>" class="btn btn-default<?= ($id == 'nginxlist') ? ' btn-primary' : '' ?>">Nginx访问列表</a>
            <a href="<?= Url::toRoute('/visit/showtotal') ?>" class="btn btn-default<?= ($id == 'showtotal') ? ' btn-primary' : '' ?>">Nginx统计</a>
            <a href="<?= Url::toRoute('/visit/iisvisit') ?>" class="btn btn-default<?= ($id == 'iisvisit') ? ' btn-primary' : '' ?>">IIS访问IP统计</a>
            <a href="<?= Url::toRoute('/visit/iislist') ?>" class="btn btn-default<?= ($id == 'iislist') ? ' btn-primary' : '' ?>">IIS访问列表</a>
            <a href="<?= Url::toRoute('/visit/iis') ?>" class="btn btn-default<?= ($id == 'iis') ? ' btn-primary' : '' ?>">IIS统计</a>
        </div>
        <div class="btn-group pull-right">
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
                                            var url = '/visit/showtatal?search_date='+ dateText;
                                            location.href = url;
                                        }"
                    ),
                ],
            ]);
            ?>
        </div>
    </div>
</div>