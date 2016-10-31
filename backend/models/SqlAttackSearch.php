<?php

namespace backend\models;

use common\models\SqlAttack;
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
class SqlAttackSearch extends SqlAttack {

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
        if (empty($params) || empty($params['SqlLogSearch']['databasetype'])) {
            $dataProvider = new ActiveDataProvider([
                'query' => $query->from("SqlAttack"),
                'sort' => [
                    'defaultOrder' => [
                        'executedate' => SORT_DESC,
                    ],
                ],
            ]);
        } else {
            //判断参数中的时间，从而选择正确的数据库
            $queryTable = 'SqlAttack';
            if (isset($params['SqlAttackSearch']['start_date'])) {
                $baseDay = $params['SqlAttackSearch']['start_date'];
                //判断当前表是不是在这3天内
                $queryDaystring = strtotime($baseDay);
                $querydaytimestring = date("Y-m-d", $queryDaystring);
                $querydayint = strtotime($querydaytimestring);
                //今天的标记daytime
                $todayint = strtotime(date('Y-m-d', time()));
                //if ($querydayint < $todayint && ($querydayint + 4 * 24 * 60 * 60) >= $todayint&&$querydayint>=  strtotime('2016-8-9')) {
                //    //判断是否在对应的天内
                //    //得到要查询的表的数据
                //    $queryTable = "SqlAttack" . date("Ymd", $querydayint);
               // }
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query->from($queryTable),
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
