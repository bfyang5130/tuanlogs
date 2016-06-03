<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\models\SqlTracePersqlSearch;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = '每天语句查询量';
$params = \Yii::$app->request->get();
//处理时间
$accLogErr = new SqlTracePersqlSearch();
if (!empty($params['SqlTracePersqlSearch']['sqlquerytime'])) {
    $accLogErr->sqlquerytime=$params['SqlTracePersqlSearch']['sqlquerytime'];
}else{
    $params['SqlTracePersqlSearch']['sqlquerytime'] = date('Y-m-d 00:00:00');
    $accLogErr->sqlquerytime=date("Y-m-d 00:00:00");
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
                                                            <label for="sqllogsearch-sqltext">数据库：</label>
                                                            <?= Html::activeDropDownList($accLogErr, 'databasetype', \backend\services\SqlTraceService::getSqlTraceDbType(), ['class' => 'form-control']) ?>

                                                            <label for="exampleInputEmail2">时间：</label>
                                                            <?=
                                                            \yii\jui\DatePicker::widget([
                                                                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                                                                'model' => $accLogErr,
                                                                'language' => 'zh-CN',
                                                                'attribute' => 'sqlquerytime',
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
                                            <th>数量</th>
                                            <th>数据库</th>
                                            <th>时间</th>
                                        </tr>
<?php foreach ($datas as $oneErrorValue): ?>
                                            <tr>
                                                <td class="center"><code><?= Html::encode($oneErrorValue['sqltext']) ?></code></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['amount']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['databasetype']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['sqlquerytime']) ?></td>
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
