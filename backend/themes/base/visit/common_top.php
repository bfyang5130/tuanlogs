<?php

use yii\helpers\Url;
?>
<div class="panel-heading">
    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
        <div class="btn-group" role="group" aria-label="First group">
            <a href="<?= Url::toRoute('/visit/index') ?>" class="btn btn-default">Nginx日志</a>
            <a href="<?= Url::toRoute('/visit/iis') ?>" class="btn btn-default">IIS日志</a>
        </div>
    </div>
</div>