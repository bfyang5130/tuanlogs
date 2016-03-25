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
    public static function findApplicationName($whereCondition = '', $whereArray = []) {
        $model = new ApplicateName();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where($whereCondition, $whereArray),
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
    public static function findAll($condition = '') {
        return ApplicateName::findAll($condition);
    }

    /**
     * 获得限制的数据记录
     * @param type $condition
     * @param type $conPrams
     * @param type $limit
     * @return type
     */
    public static function findAppliName($condition, $conPrams, $limit,$order) {
        return ApplicateName::find()->asArray()->where($condition, $conPrams)->orderBy([$order=>SORT_DESC])->limit($limit)->all();
    }

}

?>
