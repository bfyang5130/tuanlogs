<?php

use yii\helpers\Url;
use backend\models\forms\MonitorSelectForm;

$id = \Yii::$app->controller->action->id;
$postSelect = \Yii::$app->request->post();
if (!isset($postSelect['MonitorSelectForm'])) {
    $stime = Date('Y-m-d 00:00:00');
    $etime = Date('Y-m-d 00:00:00');
    $selectid=1;
} else {
    $stime = $postSelect['MonitorSelectForm']['stime'];
    $etime = $postSelect['MonitorSelectForm']['etime'];
    $selectid=$postSelect['MonitorSelectForm']['selectid'];
}
$selectFrom = new MonitorSelectForm();
$selectFrom->setAttributes(['stime' => $stime, 'etime' => $etime,'selectid'=>$selectid]);
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/server/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">常用监控</a>
            <a href="<?= Url::toRoute('/server/addmonitor') ?>" class="btn btn-default<?= ($id == 'addmonitor') ? ' btn-primary' : '' ?>">添加监控</a>
        </div>
    </div>
</div>