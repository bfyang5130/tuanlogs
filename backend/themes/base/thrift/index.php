<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use backend\models\forms\HbaseVisitForm;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = 'Hbase数据库';
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
        <div class="row">
            <?php /**
              <div class="col-md-2">
              <div class="panel panel-default">
              <div class="panel-heading">数据表列表</div>
              <?php
              //获得表信息
              $tablesList = backend\services\HbaseTableService::findTablesLists();
              ?>
              <ul class="list-group">
              <?php if (empty($tablesList)): ?>
              <li class="list-group-item">Cras justo odio</li>
              <?php
              else:
              foreach ($tablesList as $oneTable):
              ?>
              <a href="<?= Url::toRoute('/thrift/index') . '?table=' . $oneTable ?>"><li class="list-group-item"><?= $oneTable ?></li></a>
              <?php
              endforeach;
              endif;
              ?>
              </ul>
              </div>
              </div>
             */ ?>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">操作面板(查询数据小于当天)</div>
                    <div class="panel-heading">
                        <table class="table table-bordered table-striped table-condensed">
                            <tbody>
                                <?php
                                $form = ActiveForm::begin([
                                            'action' => ['/thrift/index'],
                                            'method' => 'get',
                                ]);
                                ?>
                                <tr>
                                    <td>

                                        <div class="content form-inline">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label for="exampleInputEmail2">访问日期：</label>
                                                    <?=
                                                    DateTimePicker::widget([
                                                        'language' => 'zh-CN',
                                                        'model' => $hbasevisit,
                                                        'attribute' => 'start_time',
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
                                                        'model' => $hbasevisit,
                                                        'attribute' => 'end_time',
                                                        'pickButtonIcon' => 'glyphicon glyphicon-time',
                                                        'template' => '{input}{button}',
                                                        'clientOptions' => [
                                                            'autoclose' => true,
                                                            'format' => 'yyyy-mm-dd hh:ii:ss',
                                                            'todayBtn' => true,
                                                        ],
                                                    ]);
                                                    ?>
                                                    <label for="sqllogsearch-sqltext">站点：</label>
                                                    <?= Html::activeDropDownList($hbasevisit, 'web_visit', HbaseVisitForm::webVisit(), ['class' => 'form-control']) ?>
                                                    <label for="sqllogsearch-sqltext">代理：</label>
                                                    <?= Html::activeDropDownList($hbasevisit, 'proxy', HbaseVisitForm::proxyList(), ['class' => 'form-control']) ?>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="content form-inline">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($hbasevisit, 'search_col')->dropDownList(HbaseVisitForm::colLists(), ['class' => 'form-control']) ?>
                                                    <?= $form->field($hbasevisit, 'key_word')->textInput(['class' => 'form-control']) ?>
                                                    <button style="margin-bottom: 10px;" type="submit" class="btn btn-default btn-primary btn-sm">查询</button>
                                                </div>
                                            </div>
                                        </div> 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="content form-inline">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?= $form->field($hbasevisit, 'show_check')->checkboxList(HbaseVisitForm::showlist()) ?>
                                                </div>
                                            </div>
                                        </div> 
                                    </td>
                                </tr>
                                <?php ActiveForm::end(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered">
                            <?php
                            $dataList = backend\services\HbaseTableService::findDataLists();


                            if (!empty($dataList)):
                                echo '<tr>';
                                foreach ($dataList['th'] as $oneTHD) {
                                    echo '<th>' . $oneTHD . '</th>';
                                }
                                echo '</tr>';
                                foreach ($dataList['data'] as $onerow):
                                    ?>
                                    <tr>
                                        <?php
                                        foreach ($onerow->columns as $oneCoulmn):
                                            ?>
                                            <td><?= $oneCoulmn->value ?></td>
                                            <?php
                                        endforeach;
                                        ?>
                                    </tr>
                                    <?php
                                endforeach;
                            endif;
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
