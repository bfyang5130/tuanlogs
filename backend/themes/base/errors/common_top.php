<?php

use yii\helpers\Url;

$id = \Yii::$app->controller->action->id;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/errors/index') ?>" class="btn btn-default<?= strpos($id, 'index')!==FALSE ? ' btn-primary' : '' ?>">错误日志列表</a>
            <a href="<?= Url::toRoute('/errors/trace') ?>" class="btn btn-default<?= strpos($id, 'trace')!==FALSE ? ' btn-primary' : '' ?>">跟踪日志列表</a>
            <a href="<?= Url::toRoute('/errors/doing') ?>" class="btn btn-default<?= strpos($id, 'doing')!==FALSE ? ' btn-primary' : '' ?>">视图统计</a>
        </div>
    </div>
</div>