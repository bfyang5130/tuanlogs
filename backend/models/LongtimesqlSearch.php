<?php

namespace backend\models;

use common\models\SqlTraceLong;
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
class LongtimesqlSearch extends SqlTraceLong {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['executedate', 'start_date', 'end_date', 'sqlusedtime', 'databasetype'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

//sqplit
    public static function getDb() {
        return \Yii::$app->db1;
    }

    //put your code here
    public function search($params) {
        $query = SqlTraceLong::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'db' => self::getDb(),
            'sort' => [
                'defaultOrder' => [
                    'executedate' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        // if ($this->executedate) {
        //    $this->start_date = $this->executedate;
        //     $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->start_date)));
        //}
        $query->andFilterWhere(['>=', 'sqlusedtime', $this->sqlusedtime]);
        if ($this->databasetype && $this->databasetype != 'all') {
            $query->andFilterWhere(['databasetype' => $this->databasetype]);
        }

        $query->andFilterWhere(['>=', 'executedate', $this->start_date]);
        $query->andFilterWhere(['<', 'executedate', $this->end_date]);

        return $dataProvider;
    }

}

?>
