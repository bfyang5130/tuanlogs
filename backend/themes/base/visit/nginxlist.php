<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\models\AccessLogssSearch;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'NGINX访问列表';
$params = \Yii::$app->request->get();
//为了保证实时数据，先统计一下当前最近10分钟的top50数据因为只有10分钟，所以统计会少很多时间
//处理时间
$accLogErr = new AccessLogssSearch();
if (!empty($params['AccessLogssSearch']['date_reg'])) {
    $accLogErr->date_reg = $params['AccessLogssSearch']['date_reg'];
} else {
    $params['AccessLogssSearch']['date_reg'] = date('Y-m-d 00:00:00');
    $accLogErr->date_reg = date('Y-m-d 00:00:00');
    //$params['AccessLogssSearch']['date_reg'] = '2016-06-06 00:00:00';
    //$accLogErr->date_reg = '2016-06-06 00:00:00';
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
                                                            'action' => ['/visit/nginxlist'],
                                                            'method' => 'get',
                                                ]);
                                                ?>
                                                <div class="content form-inline">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="sqllogsearch-sqltext">IP：</label>
                                                            <?= Html::activeTextInput($accLogErr, 'Ip1', ['class' => 'form-control', 'style' => 'width:200px']) ?>
                                                            <label for="exampleInputEmail2">访问日期：</label>
                                                            <?=
                                                            \yii\jui\DatePicker::widget([
                                                                'options' => ['class' => 'form-control datepicker', 'readonly' => true],
                                                                'model' => $accLogErr,
                                                                'language' => 'zh-CN',
                                                                'attribute' => 'date_reg',
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
                                            <th width="10%">访问时间</th>
                                            <th width="20%">访问地址</th>
                                            <th width="10%">状态</th>
                                            <th width="5%">大小</th>
                                            <td width="5%">耗时</td>
                                            <th width="20%">描述</th>
                                            <th width="20%">站点</th>
                                        </tr>
                                        <?php foreach ($datas as $oneErrorValue): ?>
                                            <tr>
                                                <td class="center" title="<?= Html::encode($oneErrorValue['country'] . $oneErrorValue['province'] . $oneErrorValue['city']) ?>"><?= Html::encode($oneErrorValue['Ip1']) ?></td>
                                                <td class="center" title="来源：<?= Html::encode($oneErrorValue['from_url']) ?>"><?= $oneErrorValue['date_reg'] ?></td>
                                                <td class="center" title="协议：<?= Html::encode($oneErrorValue['request_protocol']) ?>方式：<?= Html::encode($oneErrorValue['request_method']) ?>"><?= $oneErrorValue['request_url'] ?></td>
                                                <td class="center"><?= $oneErrorValue['status_code'] ?></td>
                                                <td class="center"><?= $oneErrorValue['body_size'] ?></td>
                                                <td class="center"><?= $oneErrorValue['request_time'] ?></td>
                                                <td class="center">
                                                    <p>
                                                        平台：<?= Html::encode($oneErrorValue['plat']) ?><br/>
                                                        浏览器：<?= Html::encode($oneErrorValue['bower']) ?><br/>
                                                        手机平台：<?= Html::encode($oneErrorValue['mobile_plat']) ?><br/>
                                                    </p>
                                                </td>
                                                <td class="center"><?= Html::encode($oneErrorValue['visitwebsite']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
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
