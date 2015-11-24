<?php

namespace backend\modules\ajax\controllers;

use yii\web\Controller;

class DefaultController extends Controller
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
