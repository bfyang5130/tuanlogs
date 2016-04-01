<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use backend\models\forms\MonitorForm;

$id = \Yii::$app->controller->action->id;
$search_date = Yii::$app->request->get("search_date");
if (empty($url)) {
    $url = '/visit/index';
}
$ip = Yii::$app->request->get("ip");
if (empty($ip)) {
    $ip = '192.168.8.190';
}
$toUrl = Url::toRoute($url) . '?ip=' . $ip;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/server/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">常用监控</a>
            <a href="<?= Url::toRoute('/server/addmonitor') ?>" class="btn btn-default<?= ($id == 'addmonitor') ? ' btn-primary' : '' ?>">添加监控</a>
        </div>
        <div class="btn-group pull-right">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'table-form',
                        'action' => ['server/selectmonitor'],
                        'options' => ['class' => 'form-inline']
            ]);
            ?>
            <?= Html::dropDownList("id", null, MonitorForm::findItems(), ['class' => 'form-control']) ?>
            <?=
            \yii\jui\DatePicker::widget([
                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                'attribute' => 'start_date',
                'language' => 'zh-CN',
                'dateFormat' => 'yyyy-MM-dd',
                'value' => empty($ssearch_date) ? date('Y-m-d') : $ssearch_date,
                'clientOptions' => [
                    'minDate' => '2015-01-01',
                    'maxDate' => date("Y-m-d"),
                    'onSelect' => new \yii\web\JsExpression(
                            "function (dateText, inst) {
                                            var url = '$toUrl&ssearch_date='+ dateText;
                                            location.href = url;
                                        }"
                    ),
                ],
            ]);
            ?>
            <?=
            \yii\jui\DatePicker::widget([
                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                'attribute' => 'start_date',
                'language' => 'zh-CN',
                'dateFormat' => 'yyyy-MM-dd',
                'value' => empty($esearch_date) ? date('Y-m-d') : $esearch_date,
                'clientOptions' => [
                    'minDate' => '2015-01-01',
                    'maxDate' => date("Y-m-d"),
                    'onSelect' => new \yii\web\JsExpression(
                            "function (dateText, inst) {
                                            var url = '$toUrl&esearch_date='+ dateText;
                                            location.href = url;
                                        }"
                    ),
                ],
            ]);
            ?>
            <div class="form-group">
                <?= Html::submitButton('查看', ['class' => 'btn btn-sm btn-warning', 'name' => 'dabase-button']) ?>
                <div style="width:20px;height:10px;"></div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>