<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\models\AccessLogSqlInjectSearch;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = 'sql攻击信息';
$params = \Yii::$app->request->get();
$search_date = Yii::$app->request->get("date");
//处理时间
if (!empty($search_date)) {
    $params['AccessLogSqlInjectSearch']['request_time'] = date('Y-m-d 00:00:00',  strtotime($search_date));
  }
//处理来源
$source = Yii::$app->request->get("source");
if (!empty($source)) {
    $params['AccessLogSqlInjectSearch']['source'] = $source;
}
$accLogErr = new AccessLogSqlInjectSearch();
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
                                                            'action' => ['/nginx/sqlattack'],
                                                            'method' => 'get',
                                                ]);
                                                ?>
                                                <div class="content form-inline">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label for="sqllogsearch-sqltext">用户IP：</label>
                                                            <?= Html::activeTextInput($accLogErr, 'user_ip', ['class' => 'form-control', 'style' => "width:200px"]) ?>
                                                            <label for="sqllogsearch-sqltext">来源：</label>
                                                            <?= Html::activeTextInput($accLogErr, 'source', ['class' => 'form-control', 'style' => 'width:100px']) ?>

                                                            <label for="sqllogsearch-sqltext">类型：</label>
                                                            <?= Html::activeTextInput($accLogErr, 'log_type', ['class' => 'form-control', 'style' => 'width:100px']) ?>

                                                            <label for="exampleInputEmail2">请求时间：</label>
                                                            <?=
                                                            DateTimePicker::widget([
                                                                'language' => 'zh-CN',
                                                                'model' => $accLogErr,
                                                                'attribute' => 'request_time',
                                                                'pickButtonIcon' => 'glyphicon glyphicon-time',
                                                                'template' => '{input}{button}',
                                                                'clientOptions' => [
                                                                    'autoclose' => true,
                                                                    'format' => 'yyyy-mm-dd',
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
                                            <th>用户IP</th>
                                            <th width="600px">详细内容</th>
                                            <th>请求时间</th>
                                            <th>请求域名</th>
                                            <th>来源</th>
                                        </tr>
                                        <?php foreach ($datas as $oneErrorValue): ?>
                                            <tr>
                                                <td class="center"><?= Html::encode($oneErrorValue['user_ip']) ?></td><td>
                                                    <code><?= Html::encode($oneErrorValue['access_str']) ?></code>
                                                </td>
                                                <td class="center"><?= Html::encode($oneErrorValue['request_time']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['log_type']) ?></td>
                                                <td class="center"><?= Html::encode($oneErrorValue['source']) ?></td>
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
