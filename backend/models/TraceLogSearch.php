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
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if (isset($params['id'])) {
            $query->andWhere(['ApplicationId' => $params['id']]);
        }
        if (isset($params['TraceLogSearch']['start_date']) && !empty($params['TraceLogSearch']['start_date'])) {
            $query->andWhere(" AddDate>=:start_date", [':start_date' => $params['TraceLogSearch']['start_date']]);
        }
        if (isset($params['TraceLogSearch']['end_date']) && !empty($params['TraceLogSearch']['end_date'])) {
            $query->andWhere(" AddDate<=:end_date", [':end_date' => $params['TraceLogSearch']['end_date']]);
        }
        if (isset($params['TraceLogSearch']['Parameter']) && !empty($params['TraceLogSearch']['Parameter'])) {
            $query->andWhere(['like', 'Parameter', $params['TraceLogSearch']['Parameter']]);
        }
        if (isset($params['TraceLogSearch']['Method']) && !empty($params['TraceLogSearch']['Method'])) {
            $query->andWhere(['like', 'Method', $params['TraceLogSearch']['Method']]);
        }
        if(!empty($params['TraceLogSearch']['ApplicationId'])){
            $query->andWhere(['ApplicationId'=> $params['TraceLogSearch']['ApplicationId']]);
        }
        $query->orderBy('AddDate desc ');
        return $dataProvider;
    }

}

?>
