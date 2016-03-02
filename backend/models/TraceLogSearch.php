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
        $values = $params['TraceLogSearch'];
        $query->andFilterWhere(['>','AddDate',$values['start_date']]);
        $query->andFilterWhere(['<','AddDate',$values['end_date']]);
        if (isset($params['id'])) {
            $query->andWhere(['ApplicationId' => $params['id']]);
        }
        if (isset($params['TraceLogSearch']['Parameter']) && !empty($params['TraceLogSearch']['Parameter'])) {
            $query->andWhere(['like', 'Parameter', $params['TraceLogSearch']['Parameter']]);
        }
        if (isset($params['TraceLogSearch']['Method']) && !empty($params['TraceLogSearch']['Method'])) {
            $query->andWhere(['like', 'Method', $params['TraceLogSearch']['Method']]);
        }
        if(!empty($params['TraceLogSearch']['ApplicationId']) && $params['TraceLogSearch']['ApplicationId'] != 'all'){
            $query->andWhere(['ApplicationId'=> $params['TraceLogSearch']['ApplicationId']]);
        }
        $query->orderBy('AddDate desc ');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        return $dataProvider;
    }

}

?>
