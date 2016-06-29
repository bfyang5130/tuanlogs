<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

$this->title = 'nginx统计入口';

$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
}

//$search_date = '2016-06-06';
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
                                            <td colspan="6"><h5>更多统计</h5></td>
                                        </tr>
                                        <tr>
                                            <td>日访问详情</td><td><a target="_blank" href="<?= Url::toRoute('/visit/onedtail').'?fc=totalvisit&search_date='.$search_date ?>">查看</a></td>
                                        </tr>
                                        <tr>
                                            <td>延时页面详情</td><td><a target="_blank" href="<?= Url::toRoute('/visit/onedtail').'?fc=totalvisit&search_date='.$search_date ?>">查看</a></td>
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
