<?php

namespace backend\models;

use common\models\AccessLogMost;
use yii\data\ActiveDataProvider;
use yii\base\Model;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TraceLogSearch
 *
 * @author Administrator
 */
class AccessLogMostSearch extends AccessLogMost {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['start_date', 'end_date'], 'safe'],
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
        $query = new \yii\db\Query;

        $dataProvider = new ActiveDataProvider([
            'query' => $query->from(AccessLogMost::tableName()),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        $query->orderBy('AccessIPNum desc');
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->Date_time) {
            $this->start_date = $this->Date_time;
            $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->start_date)));
        }
        $query->andFilterWhere(['Website' => $this->Website]);
        $query->andFilterWhere(['server' => $this->server]);
        $query->andFilterWhere(['>=', 'Date_time', $this->start_date]);
        $query->andFilterWhere(['<', 'Date_time', $this->end_date]);
        $query->orderBy('AccessIPNum desc');
        return $dataProvider;
    }

    /**
     * 获得数据库对应数据
     * @return type
     */
    public static function getWebsite() {
        $query = new Query();
        $query->select("Website")
                ->from("AccessLog_Most")
                ->distinct();
        $dbtypes = $query->all();
        $dbtype_item = ArrayHelper::map($dbtypes, "Website", "Website");
        return $dbtype_item;
    }

    /**
     * 获得数据库对应数据
     * @return type
     */
    public static function getServer() {
        $query = new Query();
        $query->select("server")
                ->from("AccessLog_Most")
                ->distinct();
        $dbtypes = $query->all();
        $dbtype_item = ArrayHelper::map($dbtypes, "server", "server");
        return $dbtype_item;
    }

}

?>
