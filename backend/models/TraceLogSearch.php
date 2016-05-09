<?php

namespace backend\models;

use common\models\TraceLog;
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
class TraceLogSearch extends TraceLog {

    public $start_date;
    public $end_date;
    public $id;
    public $type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'integer'],
            ['type', 'safe'],
            [['Method'], 'string', 'max' => 128],
            [['Parameter'], 'string', 'max' => 1024],
            [['Content'], 'string'],
            [['ApplicationId'], 'string'],
            [['start_date'], 'string'],
            [['end_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    //public function scenarios() {
    // bypass scenarios() implementation in the parent class
    //    return Model::scenarios();
    //}
    //put your code here
    public function search($params) {

        $this->load($params);
        $query = TraceLog::find();
        if (isset($params['TraceLogSearch']['start_date']) && !empty($params['TraceLogSearch']['start_date'])) {
            $data = date("Ym", strtotime($params['TraceLogSearch']['start_date']));
            $nowdata = date("Ym");
            if ($data < $nowdata && $data > '201601') {
                TraceLog::$tablename = 'TraceLog_' . $data;
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        } else {
            $rownums = \Yii::$app->db->createCommand("select TABLE_ROWS nums from information_schema.TABLES where TABLE_SCHEMA='Tuandai_Log' and TABLE_NAME='TraceLog'")->queryOne();
           
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'totalCount' => (int) $rownums['nums'],
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);
        }
        //print_r($rownums['nums']);exit;
        //$values = $params['TraceLogSearch'];


        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere(['ApplicationId' => $this->ApplicationId,]);
        $query->andFilterWhere(['>', 'AddDate', $this->start_date]);
        $query->andFilterWhere(['<', 'AddDate', $this->end_date]);
        $query->andFilterWhere(['like', 'Parameter', $this->Parameter]);
        $query->andFilterWhere(['like', 'Method', $this->Method]);
        $query->orderBy('AddDate desc ');
        return $dataProvider;
    }

}

?>
