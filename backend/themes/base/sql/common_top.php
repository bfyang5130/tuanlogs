<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group pull-right" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/sql/index') ?>" class="btn btn-default<?= ($id=='index') ? ' btn-primary' : '' ?>">列表</a>
            <a href="<?= Url::toRoute('/sql/sqlgraph') ?>" class="btn btn-default<?= ($id=='sqlgraph') ? ' btn-primary' : '' ?>">图形统计</a>
            <a href="<?= Url::toRoute('/sql/adddatabase') ?>" class="btn btn-default<?= ($id=='adddatabase') ? ' btn-primary' : '' ?>">增加数据库</a>
            <a href="<?= Url::toRoute('/sql/addtable') ?>" class="btn btn-default<?= ($id=='addtable') ? ' btn-primary' : '' ?>">增加表统计</a>
        </div>
    </div>
</div>