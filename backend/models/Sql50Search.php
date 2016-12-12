<?php

namespace backend\models;

use common\models\SqlTraceTop50;
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
class Sql50Search extends SqlTraceTop50 {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sqlquerytime', 'start_date', 'end_date', 'queryusemaxtime', 'databasetype'], 'safe'],
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
        if(!$this->sqlquerytime){
            $this->sqlusedtime=date("Y-m-d 00:00:00");
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->from(SqlTraceTop50::tableName()),
        ]);
        $query->groupBy('querymd5');
        $query->orderBy('queryusemaxtime desc');
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if ($this->sqlquerytime) {
            $this->start_date = $this->sqlquerytime;
            $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->start_date)));
        }
        $query->andFilterWhere(['>=', 'queryusemaxtime', $this->queryusemaxtime]);
        if ($this->databasetype && $this->databasetype != 'all') {
            $query->andFilterWhere(['databasetype' => $this->databasetype]);
        }
        $query->andFilterWhere(['>=', 'queryusemaxtime', $this->queryusemaxtime]);
        $query->andFilterWhere(['>=', 'sqlquerytime', $this->start_date]);
        $query->andFilterWhere(['<', 'sqlquerytime', $this->end_date]);
        $query->groupBy('querymd5');
        $query->orderBy('queryusemaxtime desc');
        return $dataProvider;
    }

}

?>
