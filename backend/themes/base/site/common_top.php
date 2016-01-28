<?php

use yii\helpers\Url;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/site/index') ?>" class="btn btn-default">错误日志列表</a>
            <a href="<?= Url::toRoute('/site/trace') ?>" class="btn btn-default">跟踪日志列表</a>
            <a href="<?= Url::toRoute('/site/sql') ?>" class="btn btn-default">数据库统计</a>
            <a href="<?= Url::toRoute('/site/doing') ?>" class="btn btn-default">视图统计</a>
        </div>
    </div>
</div>