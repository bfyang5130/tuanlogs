<?php

namespace backend\models;

use common\models\AccessLogErrorStatus;
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
class AccessLogErrorStatusSearch extends AccessLogErrorStatus {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['error_status', 'user_ip', 'start_date','end_date', 'source', 'log_type'], 'safe'],
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
        $query = AccessLogErrorStatus::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'request_time' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['error_status' => $this->error_status]);
        $query->andFilterWhere(['user_ip' => $this->user_ip]);
        $query->andFilterWhere(['source' => $this->source]);
        $query->andFilterWhere(['log_type' => $this->log_type]);

        $query->andFilterWhere(['>=', 'request_time', $this->start_date]);
        $query->andFilterWhere(['<=', 'request_time', $this->end_date]);

        return $dataProvider;
    }

}

?>
