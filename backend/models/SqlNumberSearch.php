<?php

namespace backend\models;

use \common\models\SqlTraceSqlNumber;
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
class SqlNumberSearch extends SqlTraceSqlNumber {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['update_time', 'start_date', 'end_date', 'databasetype'], 'safe'],
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
            'query' => $query->from(SqlTraceSqlNumber::tableName()),
        ]);
        $query->orderBy('Amount desc');
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->update_time) {
            $this->start_date = $this->update_time;
            $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->start_date)));
        }
        $query->andFilterWhere(['databasetype' => $this->databasetype]);

        if (!empty($this->databasetype)) {
            $query = new \yii\db\Query;
            $dataProvider = new ActiveDataProvider([
                'query' => $query->from(SqlTraceSqlNumber::tableName()),
            ]);
        }
        $query->select("sqltext,databasetype,update_time,sum(Amount) as sAmount");
        $query->andFilterWhere(['>=', 'update_time', $this->start_date]);
        $query->andFilterWhere(['<', 'update_time', $this->end_date]);
        $query->groupBy('sqltext_md5');
        $query->orderBy('sAmount desc');
        return $dataProvider;
    }

}

?>
