<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>日志系统</title>
        <meta name="description" content="description">
        <meta name="author" content="DevOOPS">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="/devoops/plugins/bootstrap/bootstrap.css" rel="stylesheet">
        <link href="/devoops/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet">
        <link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <link href='http://fonts.googleapis.com/css?family=Righteous' rel='stylesheet' type='text/css'>
        <link href="/devoops/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
        <link href="/devoops/plugins/fullcalendar/fullcalendar.css" rel="stylesheet">
        <link href="/devoops/plugins/xcharts/xcharts.min.css" rel="stylesheet">
        <link href="/devoops/plugins/select2/select2.css" rel="stylesheet">
        <link href="/devoops/plugins/justified-gallery/justifiedGallery.css" rel="stylesheet">
        <link href="/devoops/css/style_v1.css" rel="stylesheet">
        <link href="/devoops/plugins/chartist/chartist.min.css" rel="stylesheet">
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <!--<script src="http://code.jquery.com/jquery.js"></script>-->
        <script src="/devoops/plugins/jquery/jquery.min.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
                        <script src="http://getbootstrap.com/docs-assets/js/html5shiv.js"></script>
                        <script src="http://getbootstrap.com/docs-assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <?php $this->beginBody() ?>
    <body>
        <!--Start Header-->
        <div id="screensaver">
            <canvas id="canvas"></canvas>
            <i class="fa fa-lock" id="screen_unlock"></i>
        </div>
        <div id="modalbox">
            <div class="devoops-modal">
                <div class="devoops-modal-header">
                    <div class="modal-header-name">
                        <span>Basic table</span>
                    </div>
                    <div class="box-icons">
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="devoops-modal-inner">
                </div>
                <div class="devoops-modal-bottom">
                </div>
            </div>
        </div>
        <?= $this->render('@app/views/layouts/navbar.php'); ?>
        <!--Start Container-->
        <div id="main" class="container-fluid">
            <div class="row">
                <?= $this->render('@app/views/layouts/sidebar_left.php'); ?>
                <?= $content ?>
            </div>
        </div>
        <!--End Container-->
        <script src="/devoops/plugins/jquery-ui/jquery-ui.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/devoops/plugins/bootstrap/bootstrap.min.js"></script>
        <script src="/devoops/plugins/justified-gallery/jquery.justifiedGallery.min.js"></script>
        <script src="/devoops/plugins/tinymce/tinymce.min.js"></script>
        <script src="/devoops/plugins/tinymce/jquery.tinymce.min.js"></script>
        <!-- All functions for this theme + document.ready processing -->
        <script src="/devoops/js/devoops.js"></script>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>