<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 */

$this->title = 'Generate Routes';
$this->params['breadcrumbs'][] = [
    'label' => 'Routes',
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;
$this->render('/layouts/_sidebar');
?>
<h1>Generate Routes</h1>

<?php
echo Html::beginForm();
echo GridView::widget([
    'dataProvider' => new ArrayDataProvider([
            'allModels' => $new,
        ]),
    'columns' => [
        [
            'class' => 'yii\\grid\\CheckboxColumn',
            'checkboxOptions' => function ($model) {
                    return [
                        'value' => ArrayHelper::getValue($model, 'name'),
                        'checked' => true,
                    ];
                },
        ],
        [
            'class' => 'yii\\grid\\DataColumn',
            'attribute' => 'name',
        ]
    ]
]);
echo Html::submitButton('Append', [
    'name' => 'Submit',
    'class' => 'btn btn-primary'
]);
echo Html::endForm();
?>
