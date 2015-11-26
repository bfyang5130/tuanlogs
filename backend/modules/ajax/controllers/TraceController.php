<?php

namespace backend\modules\ajax\controllers;

use yii\web\Controller;

class TraceController extends Controller
{
    public function actionIndex()
    {
        return $this->renderAjax('index');
    }
    public function actionView()
    {
        return $this->renderAjax('default_view');
    }
}
