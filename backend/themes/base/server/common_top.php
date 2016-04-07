<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use backend\models\forms\MonitorForm;
use backend\models\forms\MonitorSelectForm;
use dosamigos\datetimepicker\DateTimePicker;

$id = \Yii::$app->controller->action->id;
$postSelect = \Yii::$app->request->post();
if (!isset($postSelect['MonitorSelectForm'])) {
    $stime = Date('Y-m-d 00:00:00');
    $etime = Date('Y-m-d 00:00:00');
} else {
    $stime = $postSelect['MonitorSelectForm']['stime'];
    $etime = $postSelect['MonitorSelectForm']['etime'];
}
$selectFrom = new MonitorSelectForm();
$selectFrom->setAttributes(['stime' => $stime, 'etime' => $etime]);
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/server/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">常用监控</a>
            <a href="<?= Url::toRoute('/server/addmonitor') ?>" class="btn btn-default<?= ($id == 'addmonitor') ? ' btn-primary' : '' ?>">添加监控</a>
            <a href="<?= Url::toRoute('/server/selectmonitor') ?>" class="btn btn-default<?= ($id == 'selectmonitor') ? ' btn-primary' : '' ?>">监控查询</a>
        </div>
        <div class="btn-group pull-right">
            <?php
            $form = ActiveForm::begin([
                        'id' => 'table-form',
                        'action' => ['server/selectmonitor'],
                        'options' => ['class' => 'form-inline']
            ]);
            ?>
            <?= $form->field($selectFrom, 'selectid')->dropDownList(MonitorForm::findItems(), ['class' => 'form-control']); ?>
            <?=
            DateTimePicker::widget([
                'language' => 'zh-CN',
                'model' => $selectFrom,
                'attribute' => 'stime',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'template' => '{input}{button}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'todayBtn' => true,
                ],
            ]);
            ?>
            <?=
            DateTimePicker::widget([
                'language' => 'zh-CN',
                'model' => $selectFrom,
                'attribute' => 'etime',
                'pickButtonIcon' => 'glyphicon glyphicon-time',
                'template' => '{input}{button}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd hh:ii:ss',
                    'todayBtn' => true,
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