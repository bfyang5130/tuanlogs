<?php

namespace backend\services;

use common\models\LogType;
use yii\data\ActiveDataProvider;
use common\models\Customlog;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AppcationNameService
 *
 * @author Administrator
 */
class LogTypeService {

    /**
     * 
     * @return \yii\data\ActiveDataProvider
     */
    public static function findLogType($whereCondition = '', $whereArray = []) {
        $model = new LogType();
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
     * @return \yii\data\ActiveDataProvider
     */
    public static function findCustomlogType($whereCondition = '', $whereArray = []) {
        $model = new Customlog();
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
    public static function findAll() {
        return ApplicateName::findAll($condition);
    }

    /**
     * return array
     */
    public static function findLogTypelists() {
        $listarray = [];
        $lists = LogType::find()->all();
        foreach ($lists as $oneItems) {
            $listarray[$oneItems->id] = $oneItems->type_cn_name;
        }
        return $listarray;
    }

}

?>
