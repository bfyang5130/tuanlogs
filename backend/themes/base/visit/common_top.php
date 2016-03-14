<?php

use yii\helpers\Url;
$id = \Yii::$app->controller->action->id;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/visit/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">Nginx日志</a>
            <a href="<?= Url::toRoute('/visit/iis') ?>" class="btn btn-default<?= ($id == 'iis') ? ' btn-primary' : '' ?>">IIS日志</a>
        </div>
    </div>
</div>