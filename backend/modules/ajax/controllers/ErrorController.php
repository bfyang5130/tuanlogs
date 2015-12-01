<?php

namespace backend\modules\ajax\controllers;

use yii\web\Controller;

class ErrorController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionView() {
        return $this->render('default_view');
    }

}
