<?php

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use backend\services\LogTypeService;
use yii\helpers\Url;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            自定义日志
        </h1>
        <?=
        Breadcrumbs::widget([
            'tag' => 'ol',
            'itemTemplate' => "<li>{link}</li>\n", // template for all links
            'links' => [
                [
                    'label' => '日志列表',
                    'url' => ['/ajax/customlog/index'],
                ]
            ]
        ]);
        ?>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">增加自定义日志</h3>
                        <div class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="<?= Url::toRoute('/ajax/customlog/add') ?>"><i class="fa fa-fw fa-plus"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
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
                                                'indent', '|',
                                                'simpleupload', 'insertimage','|',
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
                    </div><!-- /.box -->
                </div><!-- /.col -->
            </div>
        </div>
    </section><!-- /.content -->
</div>