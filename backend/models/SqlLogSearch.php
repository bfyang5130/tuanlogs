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
    public $id;
    public $type;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['id', 'integer'],
            ['type', 'safe'],
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
        $dataProvider->setSort([
            'attributes' => [
                'sqlusedtime',
            ]
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
        if (isset($params['SqlLogSearch']['sqltext']) && !empty($params['SqlLogSearch']['sqltext'])) {
            $query->andWhere(['like', 'sqltext', $params['SqlLogSearch']['sqltext']]);
        }
        $query->orderBy('executedate desc ');
        return $dataProvider;
    }

}

?>
