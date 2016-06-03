<?php

namespace backend\models;

use common\models\SqlTracePersql;
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
class SqlTracePersqlNewSearch extends SqlTracePersql {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['start_date', 'end_date', 'databasetype'], 'safe'],
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
        $query = new \yii\db\Query;

        $dataProvider = new ActiveDataProvider([
            'query' => $query->from(SqlTracePersqlSearch::tableName()),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        $query->where('is_new=1');
        $query->orderBy('amount desc');
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->sqlquerytime) {
            $this->start_date = $this->sqlquerytime;
            $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->sqlquerytime)));
        }
        $query->andFilterWhere(['databasetype' => $this->databasetype]);
        $query->andFilterWhere(['sqlquerytime' => $this->start_date]);
        $query->orderBy('amount desc');
        return $dataProvider;
    }

}

?>
