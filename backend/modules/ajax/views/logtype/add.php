<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '增加日志类型';
$this->params['breadcrumbs'][] = $this->title;
?>
<div id="page-login" class="row">
    <div class="col-xs-12 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
        <div class="box">
            <div class="box-content">
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
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var logo = document.getElementById("logo");
    if (logo === null) {
        window.location.href = '/?url=<?= $_SERVER['REQUEST_URI'] ?>';
    }
    $(".ajax-link").on("click", function() {
        var thisurl = $(this).attr("href");
        htmlobj = $.ajax({url: thisurl, async: false});
        $("#ajax-content").html(htmlobj.responseText);
        return false;
    });
</script>