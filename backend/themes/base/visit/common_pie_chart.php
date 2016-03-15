<?php

use miloschuman\highcharts\Highcharts;
?>
<div class="abc">
    <?=
    Highcharts::widget([
        'options' => [
            'chart' => [
                'type' => 'pie',
                'plotShadow' => true, //设置阴影
                'renderTo'=>'abc',
                'height' => 450,
            ],
            'title' => [
                'text' => $text
            ],
            'credits' => [
                'enabled' => false//不显示highCharts版权信息
            ],
            'plotOptions' => [
                'pie' => [
                    'allowPointSelect' => true,
                    'cursor' => 'pointer',
                    'dataLabels' => [
                        'enabled' => false
                    ],
                    'showInLegend' => true
                ],
            ],
            'legend' => [
                'verticalAlign' => "bottom",
            ],
            'series' => [$date['series']]
        ]
    ]);
    ?>
</div>