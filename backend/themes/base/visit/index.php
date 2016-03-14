<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use backend\services\NginxService;

$this->title = '访问日志';
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
                            <div class="col-lg-6">
                                <?php
                                //获得21今日访问情况
                                $userVisits = NginxService::findOneVisitis('2016-02-26', NginxService::AccessStatistic)
                                ?>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="6"><h5>21代理服务器</h5></td>
                                        </tr>
                                        <tr>
                                            <td>今日访问量：</td><td><?= $userVisits ?></td>
                                            <td>总流量：</td><td>1000G</td>
                                            <td>网站吞吐量：</td><td>10M/s</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问来源：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问方式：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我也是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问方式：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我还是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问协议：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我又是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>状态：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>哎呀，大饼，救命</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td colspan="6"><h5>17代理服务器</h5></td>
                                        </tr>
                                        <tr>
                                            <td>今日访问量：</td>
                                            <td>10</td>
                                            <td>总流量：</td>
                                            <td>1000G</td>
                                            <td>网站吞吐量：</td>
                                            <td>10M/s</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问来源：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问方式：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我也是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问方式：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我还是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>访问协议：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>大家好，我又是大饼</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-bordered table-striped table-condensed">
                                    <tbody>
                                        <tr>
                                            <td><h5>状态：</h5></td>
                                        </tr>
                                        <tr>
                                            <td>哎呀，大饼，救命</td>
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
