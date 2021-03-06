<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
$search_date = Yii::$app->request->get("search_date");
if (empty($url)) {
    $url = '/visit/api.html?fc=totalvisit';
}
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group pull-left">
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
                                            var url = '".$url."&search_date='+ dateText;
                                            location.href = url;
                                        }"
                    ),
                ],
            ]);
            ?>
        </div>
    </div>
</div>