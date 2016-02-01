<?php
/* @var $this yii\web\View */

use backend\models\ErrorLogSearch;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

$this->title = '日志列表';
#获得日志统计记录

$params = \Yii::$app->request->queryParams;
$searchModel = new ErrorLogSearch();
$dataProvider = $searchModel->search($params);
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

                <?php
                $form = ActiveForm::begin([
                            'action' => ['/site/index'],
                            'method' => 'get',
                            'options' => ['class' => 'form-inline'],
                ]);
                ?>
                <?= $form->field($searchModel, 'Method', [ 'labelOptions' => ['label' => '函数'], 'inputOptions' => ['class' => 'form-control']]) ?>

                <?= $form->field($searchModel, 'Parameter', [ 'labelOptions' => ['label' => '参数'], 'inputOptions' => ['class' => 'form-control']]) ?>

                <div class="form-group">
                    <label for="exampleInputEmail2">时间：</label>
                    <?=
                    \yii\jui\DatePicker::widget([
                        'model' => $searchModel,
                        'options' => ['class' => 'form-control'],
                        'attribute' => 'start_date',
                        'language' => 'zh-CN',
                        'dateFormat' => 'yyyy-MM-dd',
                        'value' => date('Y-m-d'),
                    ]);
                    ?>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail2">至</label>
                    <?=
                    \yii\jui\DatePicker::widget([
                        'model' => $searchModel,
                        'options' => ['class' => 'form-control'],
                        'attribute' => 'end_date',
                        'language' => 'zh-CN',
                        'dateFormat' => 'yyyy-MM-dd',
                        'value' => date('Y-m-d'),
                    ]);
                    ?>
                </div>
                <button type="submit" class="btn btn-default btn-primary btn-sm">查询</button>
                <?php ActiveForm::end(); ?>
                <?php
                $begin = $dataProvider->getPagination()->getPage() * $dataProvider->getPagination()->pageSize + 1;
                $end = $begin + $dataProvider->getPagination()->getPageSize() - 1;
                if ($begin > $end) {
                    $begin = $end;
                }
                ?>
                <div class="panel-body">
                    <div class="summary">第<b><?= $begin . '-' . $end ?></b>条，共<b><?= $dataProvider->getTotalCount() ?></b>条数据.</div>
                    <?php
                    foreach ($dataProvider->getModels() as $oneError) {
                        ?>
                        <table class="table table-striped table-bordered">
                            <tr style="background-color: #ddd;">
                                <td width="80px;">ID:</td><td><?= $oneError->Id ?></td><td>类型:</td><td><?= Html::encode($oneError->ApplicationId) ?></td><td>时间:</td><td><?= Html::encode($oneError->AddDate) ?></td>
                            </tr>
                            <tr>
                                <td>函数:</td><td colspan="5"><?= Html::encode($oneError->Method) ?></td>
                            </tr>
                            <tr>
                                <td>参数：</td><td colspan="5"><?= Html::encode($oneError->Parameter) ?></td>
                            </tr>
                            <tr>
                                <td colspan="6">内容详情</td>
                            </tr>
                            <tr>
                                <td colspan="6"><?= Html::encode($oneError->Content) ?></td>
                            </tr>
                        </table>
                        <?php
                    }
                    echo LinkPager::widget(['pagination' => $dataProvider->getPagination()]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
<?php
if (isset($params['ErrorLogSearch']['Parameter']) && !empty($params['ErrorLogSearch']['Parameter'])):
    ?>
            $("#errorlogsearch-parameter").val("<?= $params['ErrorLogSearch']['Parameter'] ?>");
    <?php
endif;
if (isset($params['ErrorLogSearch']['Method']) && !empty($params['ErrorLogSearch']['Method'])):
    ?>
            $("#errorlogsearch-method").val("<?= $params['ErrorLogSearch']['Method'] ?>");
    <?php
endif;
if (isset($params['ErrorLogSearch']['start_date']) && !empty($params['ErrorLogSearch']['start_date'])):
    ?>
            $("#errorlogsearch-start_date").val("<?= $params['ErrorLogSearch']['start_date'] ?>");
    <?php
endif;
if (isset($params['ErrorLogSearch']['end_date']) && !empty($params['ErrorLogSearch']['end_date'])):
    ?>
            $("#errorlogsearch-end_date").val("<?= $params['ErrorLogSearch']['end_date'] ?>");
    <?php
endif;
?>
    });
</script>
