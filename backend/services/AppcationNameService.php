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
    public static function findApplicationName($whereCondition='',$whereArray=[]) {
        $model = new ApplicateName();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where($whereCondition,$whereArray),
            'pagination' => [
                'pagesize' => 20,
            ]
        ]);
        return $dataProvider;
    }

    /**
     * 
     * @return type
     */
    public static function findAll() {
        return ApplicateName::findAll($condition);
    }

}

?>
