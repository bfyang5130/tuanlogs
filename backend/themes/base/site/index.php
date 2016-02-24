<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker ;
use backend\services\ToolService ;

$this->title = '日志列表';
$month_info = ToolService::getMonthFirstAndLastInfo() ;
$searchModel->start_date = empty($searchModel->start_date)?date("Y-m-d H:i:s",$month_info['str_time']):$searchModel->start_date ;
$searchModel->end_date = empty($searchModel->end_date)?date("Y-m-d 59:59:59",$month_info['end_time']):$searchModel->end_date ;
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
    ?>

    <div class="body-content">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin:10px 0px;">
                    <div class="btn-group pull-right" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute('/site/index') ?>" class="btn btn-default">列表</a>
                        <a href="<?= Url::toRoute('/site/errorgraph') ?>" class="btn btn-default">图形</a>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                            <tr>
                                <td colspan="3">
                                    <?php
                                    $form = ActiveForm::begin([
                                        'action' => ['/site/index'],
                                        'method' => 'get',
                                        'options' => ['class' => 'form-inline'],
                                    ]);
                                    ?>
                                    <?= $form->field($searchModel, 'ApplicationId', [ 'labelOptions' => ['label' => '类型'], 'inputOptions' => ['class'=>'form-control','style' => 'width:140px']])->dropDownList($application_item)->error(false); ?>

                                    <?= $form->field($searchModel, 'Method', [ 'labelOptions' => ['label' => '函数'], 'inputOptions' => ['class'=>'form-control','style' => 'width:140px']])->error(false); ?>

                                    <?= $form->field($searchModel, 'Parameter', [ 'labelOptions' => ['label' => '参数'], 'inputOptions' => ['class'=>'form-control','style' => 'width:140px']])->error(false); ?>

                                    <div class="form-group">
                                        <label for="exampleInputEmail2">时间：</label>
                                        <?= DateTimePicker::widget([
                                            'language' => 'zh-CN',
                                            'model' => $searchModel,
                                            'attribute' => 'start_date',
                                            'pickButtonIcon' => 'glyphicon glyphicon-time',
                                            'template' => '{input}{button}',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd hh:ii:ss',
                                                'todayBtn' => true,
                                            ],
                                        ]);?>
                                        <label for="exampleInputEmail2">至</label>
                                        <?= DateTimePicker::widget([
                                            'language' => 'zh-CN',
                                            'model' => $searchModel,
                                            'attribute' => 'end_date',
                                            'pickButtonIcon' => 'glyphicon glyphicon-time',
                                            'template' => '{input}{button}',
                                            'clientOptions' => [
                                                'autoclose' => true,
                                                'format' => 'yyyy-mm-dd hh:ii:ss',
                                                'todayBtn' => true,
                                            ],
                                        ]);?>
                                    </div>
                                    <button type="submit" class="btn btn-default btn-primary btn-sm">查询</button>
                                    <?php ActiveForm::end(); ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                            <tr>
                                <th width="100px">类型</th>
                                <th width="100px">时间</th>
                                <th width="300px">函数</th>
                                <th width="300px">参数</th>
                                <th>内容</th>
                            </tr>
                            <?php foreach ($datas as $oneError):?>
                                <tr>
                                    <td>
                                        <?= Html::encode($oneError->ApplicationId) ?>
                                    </td>
                                    <td class="center">
                                        <?= Html::encode(date("Y-m-d",strtotime($oneError->AddDate))) ?>
                                        <br>
                                        <?= Html::encode(date("H:i:s",strtotime($oneError->AddDate))) ?>
                                    </td>
                                    <td class="center"><code><?= Html::encode($oneError->Method) ?></code></td>
                                    <td class="center"><code><?= Html::encode($oneError->Parameter) ?></code></td>
                                    <td class="center"><code><?= Html::encode($oneError->Content) ?></code></td>
                                </tr>
                            <?php endforeach;?>
                            <tr>
                                <td colspan="11" class="text-center">
                                    <?= LinkPager::widget(['pagination' => $pager]);?>
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
<script type="text/javascript">
</script>
