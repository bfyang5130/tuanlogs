<?php

use yii\helpers\Url;
?>
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu">
            <li class="header">导航</li>
            <!-- Optionally, you can add icons to the links -->
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>日志类型</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?= Url::toRoute('/ajax/error/index') ?>">错误日志</a></li>
                    <li><a href="<?= Url::toRoute('/ajax/trace/index') ?>">跟踪日志</a></li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>自定义日志</span> <i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="<?= Url::toRoute('/ajax/logtype/index') ?>">日志类型</a></li>
                    <li><a href="<?= Url::toRoute('/ajax/customlog/index') ?>">日志记录</a></li>
                </ul>
            </li>
        </ul><!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>