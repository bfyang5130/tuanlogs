<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\models\Sql50Search;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use backend\services\SqlTraceService;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = '慢日志查询';
$params = \Yii::$app->request->get();
//为了保证实时数据，先统计一下当前最近10分钟的top50数据因为只有10分钟，所以统计会少很多时间
SqlTraceService::tofitTop50near10minute();
//处理时间
$accLogErr = new Sql50Search();
if (!empty($params['Sql50Search']['executedate'])) {
    $accLogErr->executedate=$params['Sql50Search']['executedate'];
}else{
    $params['Sql50Search']['executedate'] = date('Y-m-d 00:00:00');
    $accLogErr->executedate=date("Y-m-d 00:00:00");
}

$thisDayErrorsLists = $accLogErr->search($params);
$pager = $thisDayErrorsLists->getPagination();
$datas = $thisDayErrorsLists->getModels();
$begin = $pager->page * $pager->pageSize + 1;
$end = $begin + $pager->pageSize - 1;
if ($begin > $end) {
    $begin = $end;
}
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
                <div class="tab-content">
                    <div class="tab-pane active">
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="3">
                                                <?php
                                                $form = ActiveForm::begin([
                                                            'action' => ['/sql/sql50'],
                                                            'method' => 'get',
                                                ]);
                                                ?>
                                                <div class="content form-inline">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="sqllogsearch-sqltext">耗时：</label>
                                                            <?= Html::activeTextInput($accLogErr, 'sqlusedtime', ['class' => 'form-control', 'style' => 'width:100px']) ?>

                                                            <label for="sqllogsearch-sqltext">数据库：</label>
                                                            <?= Html::activeDropDownList($accLogErr, 'databasetype', \backend\services\SqlTraceService::getSqlTraceDbType(), ['class' => 'form-control']) ?>

                                                            <label for="exampleInputEmail2">执行时间：</label>
                                                            <?=
                                                            \yii\jui\DatePicker::widget([
                                                                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                                                                'model' => $accLogErr,
                                                                'language' => 'zh-CN',
                                                                'attribute' => 'executedate',
                                                                'value' => date('Y-m-d'),
                                                                'dateFormat' => 'php:Y-m-d',
                                                                'clientOptions' => [
                                                                    'autoclose' => true,
                                                                ]
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
                                            <th width="70%">查询语句</th>
                                            <th>耗时</th>
                                            <th>数据库</th>
                                            <th>执行时间</th>
                                        </tr>
<?php foreach ($datas as $oneErrorValue): ?>
                                            <tr>
                                                <td class="center"><code><?= Html::encode($oneErrorValue['sqltext']) ?></code></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['sqlusedtime']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['databasetype']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['executedate']) ?></td>
                                            </tr>
                                                <?php endforeach; ?>
                                        <tr>
                                            <td colspan="6" class="text-center">
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
    </div>
</div>
