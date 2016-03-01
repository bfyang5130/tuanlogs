<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group pull-right" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/sql/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">列表</a>
            <a href="<?= Url::toRoute('/sql/sqlgraph') ?>" class="btn btn-default<?= ($id == 'sqlgraph') ? ' btn-primary' : '' ?>">图形统计</a>
            <a href="<?= Url::toRoute('/sql/addstatistics') ?>" class="btn btn-default<?= ($id == 'addstatistics') ? ' btn-primary' : '' ?>">添加统计</a>
        </div>
    </div>
</div>