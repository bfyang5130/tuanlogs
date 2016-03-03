<?php

namespace backend\models;

use common\models\TraceLog;
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
class TraceLogSearch extends TraceLog {

    public $start_date;
    public $end_date;
    public $id;
    public $type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'integer'],
            ['type', 'safe'],
            [['Method'], 'string', 'max' => 128],
            [['Parameter'], 'string', 'max' => 1024],
            [['Content'], 'string'],
            [['ApplicationId'], 'string'],
            [['start_date'], 'string'],
            [['end_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    //put your code here
    public function search($params) {
        $query = TraceLog::find();
        //$values = $params['TraceLogSearch'];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['>', 'AddDate', $this->start_date]);
        $query->andFilterWhere(['<', 'AddDate', $this->end_date]);
        $query->andFilterWhere(['like', 'Parameter', $this->Parameter]);
        $query->andFilterWhere(['like', 'Method', $this->Method]);
        $query->andFilterWhere(['like', 'ApplicationId', $this->ApplicationId]);
        $query->orderBy('AddDate desc ');
        return $dataProvider;
    }

}

?>
