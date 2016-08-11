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
    public $Ip1;
    public $visitwebsite;
    public $request_time;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['start_date', 'end_date', 'Ip1', 'visitwebsite', 'request_time'], 'safe'],
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
        //判断参数中的时间，从而选择正确的数据库
        $queryTable = AccessLogss::tableName();
        if (isset($params['AccessLogssSearch']['date_reg'])) {
            $baseDay = $params['AccessLogssSearch']['date_reg'];
            //判断当前表是不是在这七天内
            $queryDaystring = strtotime($baseDay);
            $querydaytimestring = date("Y-m-d", $queryDaystring);
            $querydayint = strtotime($querydaytimestring);
            //今天的标记daytime
            $todayint = strtotime(date('Y-m-d', time()));
            if ($querydayint < $todayint && ($querydayint + 8 * 24 * 60 * 60) >= $todayint && $querydayint >= strtotime('2016-8-11')) {
                //判断是否在对应的天内
                //得到要查询的表的数据
                $queryTable = $queryTable . "_" . date("Ymd", $querydayint);
            }
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query->from($queryTable),
            'db' => self::getDb(),
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
        $query->andFilterWhere(['visitwebsite' => $this->visitwebsite]);
        $query->andFilterWhere(['>=', 'request_time', $this->request_time]);
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
