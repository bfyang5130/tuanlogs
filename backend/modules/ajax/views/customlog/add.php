<?php

use backend\models\TraceLogSearch;
use backend\models\ErrorLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use backend\services\LogTypeService;
?>
<div class="row">
    <div id="breadcrumb" class="col-xs-12">
        <a href="#" class="show-sidebar">
            <i class="fa fa-bars"></i>
        </a>
        <ol class="breadcrumb pull-left">
            <?=
            Breadcrumbs::widget([
                'itemTemplate' => "<li>{link}</li>\n", // template for all links
                'links' => [
                    [
                        'label' => '错误信息',
                        'url' => ['/ajax/error/index'],
                        'class' => 'ajax-link',
                    ]
                ]
            ]);
            ?>
        </ol>
        <div id="social" class="pull-right">
            <?php /**
              <a href="#"><i class="fa fa-google-plus"></i></a>
              <a href="#"><i class="fa fa-facebook"></i></a>
              <a href="#"><i class="fa fa-twitter"></i></a>
              <a href="#"><i class="fa fa-linkedin"></i></a>
              <a href="#"><i class="fa fa-youtube"></i></a>
             * 
             */ ?>
        </div>
    </div>
</div>
<?php
#获得具体日志统计记录
$p_get = \Yii::$app->request->get();
$params = \Yii::$app->request->queryParams;
?>
<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-search"></i>
                    <span>增加自定义日志</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                    <a class="close-link">
                        <i class="fa fa-times"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'login-form'
                ]);
                ?>
                <?= $form->field($model, 'logtype_id', [ 'labelOptions' => ['label' => '选择日志类型']])->dropDownList(LogTypeService::findLogTypelists()) ?>
                <?= $form->field($model, 'call_methods', [ 'labelOptions' => ['label' => '调用的函数']]) ?>
                <?= $form->field($model, 'call_parameter', [ 'labelOptions' => ['label' => '调用的参数']]) ?>
                <?=
                \kucha\ueditor\UEditor::widget(
                        [
                            'model' => $model,
                            'attribute' => 'errormsg',
                            'clientOptions' => [
                                //编辑区域大小
                                'initialFrameHeight' => '200',
                                //设置语言
                                'lang' => 'zh-cn', //中文为 zh-cn
                                //定制菜单
                                'toolbars' => [
                                    [
                                        'fullscreen', 'source', 'undo', 'redo', '|',
                                        'fontsize',
                                        'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'removeformat',
                                        'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|',
                                        'forecolor', 'backcolor', '|',
                                        'lineheight', '|',
                                        'indent', '|'
                                    ],
                                ]
                            ]
                        ]
                );
                ?>
                <div class="text-center">
                    <?= Html::submitButton('确认提交', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var logo = document.getElementById("logo");
    if (logo === null) {
        window.location.href = '/?url=<?= $_SERVER['REQUEST_URI'] ?>';
    }
    $(document).ready(function() {
        $(".ajax-link").on("click", function() {
            var thisurl = $(this).attr("href");
            htmlobj = $.ajax({url: thisurl, async: false});
            $("#ajax-content").html(htmlobj.responseText);
            return false;
        });
    });

</script>