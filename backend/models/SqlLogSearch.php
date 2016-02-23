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
    public $time_start;
    public $time_end;
    public $databasetype;
    public $id;
    public $type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'integer'],
            [['type','sqlusedtime','start_date','time_start','time_end','databasetype','end_date','sqltext'], 'safe'],
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
        $query = SqlLogSearch::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if (isset($params['id'])) {
            $query->andWhere(['Id' => $params['id']]);
        }
        if (isset($params['SqlLogSearch']['start_date']) && !empty($params['SqlLogSearch']['start_date'])) {
            $query->andWhere(" executedate>=:start_date", [':start_date' => $params['SqlLogSearch']['start_date']]);
        }
        if (isset($params['SqlLogSearch']['end_date']) && !empty($params['SqlLogSearch']['end_date'])) {
            $query->andWhere(" executedate<=:end_date", [':end_date' => $params['SqlLogSearch']['end_date']]);
        }
        if (isset($params['SqlLogSearch']['time_start']) && !empty($params['SqlLogSearch']['time_start'])) {
            $query->andWhere(" sqlusedtime>=:time_start", [':time_start' => $params['SqlLogSearch']['time_start']]);
        }
        if (isset($params['SqlLogSearch']['time_end']) && !empty($params['SqlLogSearch']['time_end'])) {
            $query->andWhere(" sqlusedtime<=:time_end", [':time_end' => $params['SqlLogSearch']['time_end']]);
        }
        
        if (isset($params['SqlLogSearch']['sqltext']) && !empty($params['SqlLogSearch']['sqltext'])) {
            $query->andWhere(['like', 'sqltext', $params['SqlLogSearch']['sqltext']]);
        }
        if (isset($params['SqlLogSearch']['databasetype']) && !empty($params['SqlLogSearch']['databasetype'])) {
            $query->andWhere(" executedate<=:databasetype", [':databasetype' => $params['SqlLogSearch']['databasetype']]);
        }
        if (!isset($params['sort'])) {
            $query->orderBy('executedate desc ');
        }
        return $dataProvider;
    }

}

?>
