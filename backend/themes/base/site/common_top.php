<?php

use yii\helpers\Url;
$id=\Yii::$app->controller->action->id;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/site/index') ?>" class="btn btn-default<?= ($id=='index')?' btn-primary':'' ?>">错误日志列表</a>
            <a href="<?= Url::toRoute('/site/trace') ?>" class="btn btn-default<?= ($id=='trace')?' btn-primary':'' ?>">跟踪日志列表</a>
            <a href="<?= Url::toRoute('/site/sql') ?>" class="btn btn-default<?= ($id=='sql')?' btn-primary':'' ?>">数据库统计</a>
            <a href="<?= Url::toRoute('/site/doing') ?>" class="btn btn-default<?= ($id=='doing')?' btn-primary':'' ?>">视图统计</a>
        </div>
    </div>
</div>