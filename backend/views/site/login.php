<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '系统登录';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="page-login" class="row">
    <div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="text-right">
            <a href="page_register.html" class="txt-default">没有帐号?</a>
        </div>
        <div class="box">
            <div class="box-content">
                <div class="text-center">
                    <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
                </div>
                <?php
                $form = ActiveForm::begin([
                            'id' => 'login-form'
                ]);
                ?>

                <?= $form->field($model, 'username', [ 'labelOptions' => ['label' => '管理员帐号']]) ?>

                <?= $form->field($model, 'password', [ 'labelOptions' => ['label' => '管理员密码']])->passwordInput() ?>

                <div class="checkbox">
                    <label>
                        <input name ="LoginForm[remeberMe]"type="checkbox" checked=""> 记住我?
                        <i class="fa fa-square-o small"></i>
                    </label>
                </div>

                <div class="text-center">
                    <?= Html::submitButton('确认登录', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>