<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

$this->title = '增加日志类型';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            类型列表
        </h1>
        <?=
        Breadcrumbs::widget([
            'tag' => 'ol',
            'itemTemplate' => "<li>{link}</li>\n", // template for all links
            'links' => [
                [
                    'label' => '自定义',
                    'url' => ['/ajax/logtype/index']
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
                        <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
                        <div class="box-tools">
                            <ul class="pagination pagination-sm no-margin pull-right">
                                <li><a href="<?= Url::toRoute('/ajax/logtype/index') ?>"><i class="fa fa-fw fa-mail-reply"></i></a></li>
                            </ul>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <div class="text-center">
                            <h3 class="page-header"><?= Html::encode($this->title) ?></h3>
                        </div>
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => 'login-form'
                        ]);
                        ?>

                        <?= $form->field($model, 'type_name', [ 'labelOptions' => ['label' => '英文标识']]) ?>

                        <?= $form->field($model, 'type_cn_name', [ 'labelOptions' => ['label' => '中文名称']]) ?>


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