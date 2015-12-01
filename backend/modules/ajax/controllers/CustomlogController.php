<?php

namespace backend\modules\ajax\controllers;

use yii\web\Controller;
use common\models\Customlog;

class CustomlogController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionView() {
        return $this->renderAjax('default_view');
    }

    public function actionAdd() {
        $model = new Customlog();
        if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->save()) {
            $this->redirect('/ajax/customlog/index.html');
        } else {
            return $this->render('add', ['model' => $model]);
        }
    }

    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix" => "http://" . $_SERVER['HTTP_HOST'], //图片访问路径前缀
                    "imagePathFormat" => "/upload/image/{yyyy}{mm}{dd}/{time}{rand:6}" //上传保存路径
                ],
            ]
        ];
    }

}
