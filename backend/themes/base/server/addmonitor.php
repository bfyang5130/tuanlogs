<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = '添加监控项目';
#获得日志统计记录
?>
<div class="site-index">
    <?php
    echo Breadcrumbs::widget([
        'itemTemplate' => "<li><i>{link}</i></li>\n", // template for all links
        'links' => [
            [
                'label' => '首页'
            ],
        ],
    ]);
    $id = \Yii::$app->controller->action->id;
    ?>
    <div class="body-content">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane active">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                                <tr>
                                    <td colspan="3">
                                        <div class="content form-inline">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php if ($databaseFit == 1): ?>
                                                        <p style="color:green;">添加成功</p>
                                                    <?php elseif ($databaseFit == 2): ?>
                                                        <p style="color:red;">添加失败</p>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="col-md-12">
                                                    <?php
                                                    $form = ActiveForm::begin([
                                                                'id' => 'table-form',
                                                                'action' => ['server/addmonitor'],
                                                                'options' => ['class' => 'form-inline']
                                                    ]);
                                                    ?>
                                                    <?= $form->field($monitorForm, 'monitor_name', [ 'template' => "{label}\n{input}\n{hint}\n<div style='height:30px;'>{error}</div>", 'labelOptions' => ['label' => '监控项目名称']]) ?>
                                                    <?= $form->field($monitorForm, 'monitor_host', [ 'template' => "{label}\n{input}\n{hint}\n<div style='height:30px;'>{error}</div>", 'labelOptions' => ['label' => '监控主机']]) ?>
                                                    <?= $form->field($monitorForm, 'monitor_item', [ 'template' => "{label}\n{input}\n{hint}\n<div style='height:30px;'>{error}</div>", 'labelOptions' => ['label' => '监控项目ID']]) ?>
                                                    <div class="form-group">
                                                        <?= Html::submitButton('提交', ['class' => 'btn btn-sm btn-warning', 'name' => 'dabase-button']) ?>
                                                        <div style="width:20px;height:40px;"></div>
                                                    </div>
                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>