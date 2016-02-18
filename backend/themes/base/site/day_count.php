<?php
/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use nirvana\showloading\ShowLoadingAsset;
use miloschuman\highcharts\Highcharts ;

ShowLoadingAsset::register($this);

$this->title = '错误日志-日统计';
$page = Yii::$app->request->get("page") ;
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

    <div class="body-content" id="text_body_c">
        <div class="panel panel-default">
            <?= $this->render('common_top.php'); ?>
            <div class="panel-body">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group pull-left" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute(['/site/countday',"page"=>$pre_page]) ?>" class="btn btn-default">上一页</a>
                        <a href="<?= Url::toRoute(['/site/countday',"page"=>$next_page]) ?>" class="btn btn-default">下一页</a>
                    </div>
                    <div class="btn-group pull-right" role="group" aria-label="First group">
                        <a href="<?= Url::toRoute(['/site/errorgraph']) ?>" class="btn btn-default">返回</a>
                    </div>
                </div>
                <div>
                    <?php
                    echo Highcharts::widget([
                        'options'=>[
                            'chart' => [
                                'type'=> 'bar',
                                'plotShadow'=> false ,//设置阴影
                                'height'=>2800,
                            ],
                            'title' => [
                                'text' => '错误日志日统计'
                            ],
                            'credits' => [
                                'enabled'=>false//不显示highCharts版权信息
                            ],
                            'xAxis' => [
                                'categories' => $appnames,
                                'title' => array('text' => null) ,
                            ],
                            'yAxis' => [
                                'min' => 0,
                                'title' => array('text' => ''),
                                'align' => 'high',
                                'labels'=> array("overflow"=>"justify")
                            ],
                            'plotOptions'=>[
                                'bar'=>[
                                    'dataLabels'=>[
                                        'enabled'=>true
                                    ]
                                ],
                                'series'=>[
                                    'cursor'=>'pointer',
                                    'events' => array("click"=>new \yii\web\JsExpression(
                                        'function(e){
                                             var search_data = this.name ;
                                             var arr = search_data.split("-");
                                             var newdt = new Date(Number(arr[0]),Number(arr[1])-1,Number(arr[2])+1);
                                             var end_month = newdt.getMonth()+1 ;
                                             var end_day = newdt.getDate() ;
                                             var end_year = newdt.getFullYear() ;
                                             var end_date = end_year+"-"+end_month+"-"+end_day ;
                                            var target_url = "/site/index.html?ErrorLogSearch[start_date]="+this.name+"&ErrorLogSearch[end_date]="+end_date+"&ErrorLogSearch[ApplicationId]="+e.point.category;
                                            window.open(target_url);
                                         }'
                                    ))
                                ]
                            ],
                            'legend'=>[
                                'layout'=>'horizontal',
                                'align'=>'center',
                                'verticalAlign'=>'top',
                                'floating'=>true,
//                                'x'=>90,
                                'y'=>20,
                                'borderWidth'=>1,
                                'backgroundColor'=>'#FFFFFF',
                                'shadow'=>true,
                            ],
                            'tooltip'=>[
                                'enabled'=>false,
                            ],
                            'series' => $series
                        ]
                    ]);
                    ?>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    //    $(document).ready(function() {
    //        $("#text_body_c").showLoading();
    //        $.ajax({
    //            url: "/site/getdata.html?type=1",
    //            dataType: 'html',
    //            success: function() {
    //                $(this).addClass("done");
    //            },
    //            error: function() {
    //                $(this).addClass("done");
    //            }
    //        });
    //    });
</script>