<?php

namespace backend\models;

use common\models\AccessLogss;
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
class AccessLogssSearch extends AccessLogss {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['start_date', 'end_date','Ip1'], 'safe'],
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
            'query' => $query->from(AccessLogss::tableName()),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        $query->orderBy('Id desc');
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        if ($this->date_reg) {
            $this->start_date = $this->date_reg;
            $this->end_date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($this->start_date)));
        }
        $query->andFilterWhere(['Ip1' => $this->Ip1]);
        $query->andFilterWhere(['>=', 'date_reg', $this->start_date]);
        $query->andFilterWhere(['<', 'date_reg', $this->end_date]);
        $query->orderBy('date_reg desc');
        return $dataProvider;
    }

    /**
     * 获得数据库对应数据
     * @return type
     */
    public static function getWebsite() {
        $query = new Query();
        $query->select("Website")
                ->from("AccessLog_Iismost")
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
                ->from("AccessLog_Iismost")
                ->distinct();
        $dbtypes = $query->all();
        $dbtype_item = ArrayHelper::map($dbtypes, "server", "server");
        return $dbtype_item;
    }

}

?>
