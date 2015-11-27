<?php
use yii\helpers\Url;
$ul_get = \Yii::$app->request->get();
?>
<header class="navbar">
    <div class="container-fluid expanded-panel">
        <div class="row">
            <div id="logo" class="col-xs-12 col-sm-2">
                <a href="/">日志系统</a>
                <?php
                if (isset($ul_get['url']) && !empty($ul_get['url'])):
                    $url=  substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], 'url')+4);
                    ?>
                    <input type="hidden" id="re_url" name="re_url" value="<?= $url ?>"/>
                    <?php
                endif;
                ?>
            </div>
            <div id="top-panel" class="col-xs-12 col-sm-10">
                <div class="row">
                    <div class="col-xs-8 col-sm-4">
                        <div id="search">
                            <input type="text" placeholder="search"/>
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-8 top-panel-right">
                        <a href="#" class="about">系统设置</a>
                        <a class="ajax-link" href="/admin/rbac.html">权限控制</a>
                        <a href="index_v1.html" class="style1"></a>
                        <ul class="nav navbar-nav pull-right panel-menu">
                            <li class="hidden-xs">
                                <a href="index.html" class="modal-link">
                                    <i class="fa fa-bell"></i>
                                    <span class="badge">7</span>
                                </a>
                            </li>
                            <li class="hidden-xs">
                                <a class="ajax-link" href="/devoops/ajax/calendar.html">
                                    <i class="fa fa-calendar"></i>
                                    <span class="badge">7</span>
                                </a>
                            </li>
                            <li class="hidden-xs">
                                <a href="/devoops/ajax/page_messages.html" class="ajax-link">
                                    <i class="fa fa-envelope"></i>
                                    <span class="badge">7</span>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle account" data-toggle="dropdown">
                                    <div class="avatar">
                                        <img src="/devoops/img/avatar.jpg" class="img-circle" alt="avatar" />
                                    </div>
                                    <i class="fa fa-angle-down pull-right"></i>
                                    <div class="user-mini pull-right">
                                        <span class="welcome">Welcome,</span>
                                        <span>Jane Devoops</span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-user"></i>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/devoops/ajax/page_messages.html" class="ajax-link">
                                            <i class="fa fa-envelope"></i>
                                            <span>Messages</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/devoops/ajax/gallery_simple.html" class="ajax-link">
                                            <i class="fa fa-picture-o"></i>
                                            <span>Albums</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/devoops/ajax/calendar.html" class="ajax-link">
                                            <i class="fa fa-tasks"></i>
                                            <span>Tasks</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <i class="fa fa-cog"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::toRoute('/site/logout') ?>">
                                            <i class="fa fa-power-off"></i>
                                            <span>退出系统</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!--End Header-->