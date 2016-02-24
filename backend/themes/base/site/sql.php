<?php
/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;
use backend\services\SqlTraceService;
use backend\services\ToolService;
use yii\helpers\Url;

$this->title = '日志列表';
$month_info = ToolService::getMonthFirstAndLastInfo();
$searchModel->start_date = empty($searchModel->start_date) ? date("Y-m-d H:i:s", $month_info['str_time']) : $searchModel->start_date;
$searchModel->end_date = empty($searchModel->end_date) ? date("Y-m-d H:i:s", $month_info['end_time']) : $searchModel->end_date;
$begin = $pager->page * $pager->pageSize + 1;
$end = $begin + $pager->pageSize - 1;
if ($begin > $end) {
    $begin = $end;
}
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
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="margin:10px 0px;">
                    <div class="btn-group pull-right" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute('/site/sql') ?>" class="btn btn-default<?= strpos($id, 'graph')===FALSE ? ' btn-primary' : '' ?>">列表</a>
                        <a href="<?= Url::toRoute('/site/sqlgraph') ?>" class="btn btn-default<?= strpos($id, 'graph')!==FALSE ? ' btn-primary' : '' ?>">图形</a>
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
                                                    'action' => ['/site/sql'],
                                                    'method' => 'get',
                                        ]);
                                        ?>
                                        <div class="content form-inline">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <label for="sqllogsearch-sqltext">语句：</label>
<?= Html::activeTextInput($searchModel, 'sqltext', ['class' => 'form-control', 'style' => "width:1060px"]) ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="content form-inline" style="padding-top: 40px">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="sqllogsearch-sqltext">耗时：</label>
                                                    <?= Html::activeTextInput($searchModel, 'start_sqlusedtime', ['class' => 'form-control', 'style' => 'width:100px']) ?>至
<?= Html::activeTextInput($searchModel, 'end_sqlusedtime', ['class' => 'form-control', 'style' => 'width:100px']) ?>

                                                    <label for="sqllogsearch-sqltext">类型：</label>
<?= Html::activeDropDownList($searchModel, 'databasetype', SqlTraceService::getSqlTraceDbType(), ['class' => 'form-control']) ?>

                                                    <label for="exampleInputEmail2">执行时间：</label>
                                                    <?=
                                                    DateTimePicker::widget([
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
                                                    ]);
                                                    ?>
                                                    <label for="exampleInputEmail2">至</label>
                                                    <?=
                                                    DateTimePicker::widget([
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
                                                    ]);
                                                    ?>
                                                    <button type="submit" class="btn btn-default btn-primary btn-sm">查询</button>
                                                </div>
                                            </div>
                                        </div>
<?php ActiveForm::end(); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="summary">第<b><?= $begin . '-' . $end ?></b>条，共<b><?= $pager->totalCount ?></b>条数据.</div>
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                                <tr>
                                    <th width="820px">语句</th>
                                    <th>耗时(ms)</th>
                                    <th>执行时间</th>
                                    <th>类型</th>
                                </tr>
<?php foreach ($datas as $sql): ?>
                                    <tr>
                                        <td>
                                            <code><?= Html::encode($sql->sqltext) ?></code>
                                        </td>
                                        <td class="center"><?= Html::encode($sql->sqlusedtime) ?></td>
                                        <td class="center"><?= Html::encode($sql->executedate) ?></td>
                                        <td class="center"><?= Html::encode($sql->databasetype) ?></td>
                                    </tr>
<?php endforeach; ?>
                                <tr>
                                    <td colspan="11" class="text-center">
<?= LinkPager::widget(['pagination' => $pager]); ?>
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
    $(document).ready(function() {
    });
</script>