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
            [['executedate',  'start_date', 'end_date', 'sqlusedtime', 'databasetype'], 'safe'],
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
            'query' => $query->from(SqlTraceTop50::tableName()),
        ]);
        $query->where('1=1 order by DATE_FORMAT(executedate,"%Y-%m-%d") desc,sqlusedtime desc');
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if ($this->executedate) {
            $this->start_date = $this->executedate;
            $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->start_date)));
        }
        $query->andFilterWhere(['>=', 'sqlusedtime', $this->sqlusedtime]);
        $query->andFilterWhere(['databasetype' => $this->databasetype]);

        $query->andFilterWhere(['>=', 'executedate', $this->start_date]);
        if(!empty($this->databasetype)){
            $query = new \yii\db\Query;
            $dataProvider = new ActiveDataProvider([
            'query' => $query->from('(SELECT *
			FROM SqlTrace_top50    where databasetype="'.$this->databasetype.'"
			order by sqlusedtime desc) sql_tmp ')
        ]);
            $query->andFilterWhere(['>=', 'sqlusedtime', $this->sqlusedtime]);
            $query->andFilterWhere(['>=', 'executedate', $this->start_date]);
            $query->groupBy('sqltext_md5');
            $query->orderBy('sqlusedtime desc');
        }
        return $dataProvider;
    }

}

?>
