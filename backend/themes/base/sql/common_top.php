<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group pull-right" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/sql/index') ?>" class="btn btn-default<?= ($id == 'index') ? ' btn-primary' : '' ?>">列表</a>
            <a href="<?= Url::toRoute('/sql/longtimesql') ?>" class="btn btn-default<?= ($id == 'longtimesql') ? ' btn-primary' : '' ?>">慢日志查询</a>
            <a href="<?= Url::toRoute('/sql/sql50') ?>" class="btn btn-default<?= ($id == 'sql50') ? ' btn-primary' : '' ?>">50数据执行</a>
            <?php /**
            <a href="<?= Url::toRoute('/sql/sqlnums') ?>" class="btn btn-default<?= ($id == 'sqlnums') ? ' btn-primary' : '' ?>">查询排行表</a>
             */
            ?>
            <a href="<?= Url::toRoute('/sql/persqlquery') ?>" class="btn btn-default<?= ($id == 'persqlquery') ? ' btn-primary' : '' ?>">每天语句查询量</a>
            <a href="<?= Url::toRoute('/sql/sqlnewadd') ?>" class="btn btn-default<?= ($id == 'sqlnewadd') ? ' btn-primary' : '' ?>">新增语句列表</a>
            <a href="<?= Url::toRoute('/sql/sqlattack') ?>" class="btn btn-default<?= ($id == 'sqlattack') ? ' btn-primary' : '' ?>">疑攻击列表</a>
            <a href="<?= Url::toRoute('/sql/sqlgraph') ?>" class="btn btn-default<?= ($id == 'sqlgraph') ? ' btn-primary' : '' ?>">图形统计</a>
            <a href="<?= Url::toRoute('/sql/addstatistics') ?>" class="btn btn-default<?= ($id == 'addstatistics') ? ' btn-primary' : '' ?>">添加统计</a>
        </div>
    </div>
</div>