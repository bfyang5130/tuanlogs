<?php

namespace backend\modules\ajax\controllers;

use yii\web\Controller;
use common\models\LogType;

class LogtypeController extends Controller {

    public function actionIndex() {
            return $this->render('index');
    }

    public function actionAdd() {
        $model = new LogType();
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            return $this->render('add', ['model' => $model]);
        } else {
            return $this->render('add', ['model' => $model]);
        }
    }

}
