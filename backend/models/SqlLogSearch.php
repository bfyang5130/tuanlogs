<?php

namespace backend\models;

use common\models\SqlTrace;
use yii\data\ActiveDataProvider;
use yii\base\Model;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SqlLogSearch
 *
 * @author Administrator
 */
class SqlLogSearch extends SqlTrace {

    public $start_date;
    public $end_date;
    public $start_sqlusedtime;
    public $end_sqlusedtime;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['sqltext', 'start_date', 'end_date', 'start_sqlusedtime', 'end_sqlusedtime', 'databasetype'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        $query = new \yii\db\Query;
        if (empty($params)||empty($params['databasetype'])) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query->from("SqlTrace"),
                'sort' => [
                    'defaultOrder' => [
                        'executedate' => SORT_DESC,
                    ],
                ],
            ]);
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $query->from("SqlTrace ignore index (PRIMARY)"),
                'sort' => [
                    'defaultOrder' => [
                        'executedate' => SORT_DESC,
                    ],
                ],
            ]);
        }


        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['databasetype' => $this->databasetype]);

        $query->andFilterWhere(['like', 'sqltext', $this->sqltext]);

        $query->andFilterWhere(['>=', 'sqlusedtime', $this->start_sqlusedtime]);
        $query->andFilterWhere(['<=', 'sqlusedtime', $this->end_sqlusedtime]);

        $query->andFilterWhere(['>=', 'executedate', $this->start_date]);
        $query->andFilterWhere(['<=', 'executedate', $this->end_date]);


        return $dataProvider;
    }

}

?>
