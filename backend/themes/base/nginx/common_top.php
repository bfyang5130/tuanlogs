<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
$search_date = Yii::$app->request->get("search_date");
if(empty($url)){
   $url='/visit/index'; 
}
$ip = Yii::$app->request->get("ip");
if (empty($ip)) {
    $ip = '192.168.8.190';
}
$toUrl=Url::toRoute($url).'?ip='.$ip;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/visit/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">Nginx日志</a>
            <a href="<?= Url::toRoute('/visit/iis') ?>" class="btn btn-default<?= ($id == 'iis') ? ' btn-primary' : '' ?>">IIS日志</a>
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
                                            var url = '$toUrl&search_date='+ dateText;
                                            location.href = url;
                                        }"
                    ),
                ],
            ]);
            ?>
        </div>
    </div>
</div>