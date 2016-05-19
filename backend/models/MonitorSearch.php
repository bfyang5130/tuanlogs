<?php

namespace backend\models;

use common\models\Monitor;
use yii\data\ActiveDataProvider;
use yii\base\Model;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TraceLogSearch
 *
 * @author Administrator
 */
class MonitorSearch extends Monitor {


    /**
     * @inheritdoc
     */
    //public function scenarios() {
    // bypass scenarios() implementation in the parent class
    //    return Model::scenarios();
    //}
    //put your code here
    public function search($params) {

        $this->load($params);
        $query = Monitor::find();
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        //print_r($rownums['nums']);exit;
        //$values = $params['TraceLogSearch'];
        return $dataProvider;
    }

}

?>
