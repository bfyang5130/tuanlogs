<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\models\AccessLogMostSearch;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'IP重复访问最多数据';
$params = \Yii::$app->request->get();
//为了保证实时数据，先统计一下当前最近10分钟的top50数据因为只有10分钟，所以统计会少很多时间
//处理时间
$accLogErr = new AccessLogMostSearch();
if (!empty($params['AccessLogMostSearch']['Date_time'])) {
    $accLogErr->Date_time = $params['AccessLogMostSearch']['Date_time'];
} else {
    $params['AccessLogMostSearch']['Date_time'] = date('Y-m-d 00:00:00', strtotime('-1 day', time()));
    $accLogErr->Date_time = date('Y-m-d 00:00:00', strtotime('-1 day', time()));
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
                                                            'action' => ['/visit/index'],
                                                            'method' => 'get',
                                                ]);
                                                ?>
                                                <div class="content form-inline">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="sqllogsearch-sqltext">站点：</label>
                                                            <?= Html::activeDropDownList($accLogErr, 'Website', AccessLogMostSearch::getWebsite(), ['class' => 'form-control']) ?>
                                                            <label for="sqllogsearch-sqltext">服务器：</label>
                                                            <?= Html::activeDropDownList($accLogErr, 'server', AccessLogMostSearch::getServer(), ['class' => 'form-control']) ?>
                                                            <label for="exampleInputEmail2">访问日期：</label>
                                                            <?=
                                                            \yii\jui\DatePicker::widget([
                                                                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                                                                'model' => $accLogErr,
                                                                'language' => 'zh-CN',
                                                                'attribute' => 'Date_time',
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
                                            <th width="10%">访问IP</th>
                                            <th width="5%">访问量</th>
                                            <th>最多访问地址</th>
                                            <th width="5%">此地址访问次数</th>
                                            <th width="10%">访问的站点</th>
                                            <th width="20%">分流到的服务器</th>
                                            <th width="10%">访问的时间</th>
                                        </tr>
                                        <?php foreach ($datas as $oneErrorValue): ?>
                                            <tr>
                                                <td class="center"><code><?= Html::encode($oneErrorValue['AccessIP']) ?></code></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['AccessIPNum']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['Most_Address']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['Most_AddressNum']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['Website']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['server']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['Date_time']) ?></td>
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
