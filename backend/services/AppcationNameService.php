<?php

namespace backend\services;

use common\models\ApplicateName;
use yii\data\ActiveDataProvider;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppcationNameService
 *
 * @author Administrator
 */
class AppcationNameService {

    /**
     * 
     * @return \yii\data\ActiveDataProvider
     */
    public static function findApplicationName() {
        $model = new ApplicateName();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find(),
            'pagination' => [
                'pagesize' => 20,
            ]
        ]);
        return $dataProvider;
    }

}

?>
