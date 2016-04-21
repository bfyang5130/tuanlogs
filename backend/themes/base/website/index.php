
<?php
/* @var $this yii\web\View */

use yii\bootstrap\Modal;
use yii\widgets\Breadcrumbs;
use backend\services\NginxService;
use backend\services\NginxHightchartService;
use miloschuman\highcharts\Highcharts;
use yii\helpers\Url;
use miloschuman\highcharts\Highmaps;
use yii\web\JsExpression;
use yii\web\View;

$this->registerJsFile('/base/js/echarts-all-3.js', [
    'position' => View::POS_HEAD
]);
$this->title = '用户页面访问图';
$id = \Yii::$app->request->get('id');
if (!$id) {
    $id = 0;
}
$search_date = Yii::$app->request->get("search_date");
if (empty($search_date)) {
    $search_date = date('Y-m-d');
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
                            <div class="col-lg-12" id="main"  style="width: 800px;height:2000px;">
                                <script type="text/javascript">
                                    var myChart = echarts.init(document.getElementById('main'));
                                    myChart.showLoading();
                                    $.get('/website/product.html?id=<?= $id ?>', function(data) {
                                        myChart.hideLoading();

                                        myChart.setOption(option = {
                                            title: {
                                                text: '用户访问图'
                                            },
                                            tooltip: {
                                                show: true,
                                                trigger: 'item',
                                                triggerOn: 'mousemove'

                                            },
                                            series: [
                                                {
                                                    type: 'sankey',
                                                    layout: 'none',
                                                    data: data.nodes,
                                                    links: data.links,
                                                    itemStyle: {
                                                        normal: {
                                                            borderWidth: 1,
                                                            borderColor: '#aaa'
                                                        }
                                                    },
                                                    lineStyle: {
                                                        normal: {
                                                            curveness: 0.5
                                                        }
                                                    }
                                                }
                                            ]
                                        });
                                    });
                                    myChart.on('click', function(params) {

                                        var markId = params.name;
                                        var id=1;
                                        if (markId.indexOf("[") === 0) {
                                            id = markId.substring(markId.indexOf("[") + 1, markId.indexOf("]"));
                                        } else {
                                            id = markId.substring(markId.lastIndexOf("[") + 1, markId.lastIndexOf("]"));
                                        }
                                        window.open('/website/index.html?id=' + id);
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
