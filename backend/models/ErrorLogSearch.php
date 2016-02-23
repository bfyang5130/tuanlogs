<?php

namespace backend\models;

use common\models\ErrorLog;
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
class ErrorLogSearch extends ErrorLog {

    public $start_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['ApplicationId', 'Parameter' , 'Method','start_date','end_date'], 'safe'],
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
        $query = ErrorLog::find();

        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort' => [
                    'defaultOrder' => [
                        'AddDate' => SORT_DESC,
                    ],
                ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if($this->ApplicationId!="all") {
            $query->andFilterWhere([
                    'ApplicationId' => $this->ApplicationId,
            ]);
        }

        $query->andFilterWhere(['>=', 'AddDate', $this->start_date]) ;
        $query->andFilterWhere(['<=', 'AddDate', $this->end_date]) ;

        $query->andFilterWhere(['like', 'Parameter', $this->Parameter])
            ->andFilterWhere(['like', 'Method', $this->Method]);

        return $dataProvider;
    }

}

?>
