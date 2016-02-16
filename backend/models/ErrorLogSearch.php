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
        $query = ErrorLog::find();
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 10],
        ]);
        $query->orderBy('AddDate desc');
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if (isset($params['id'])) {
            $query->andWhere(['ApplicationId' => $params['id']]);
        }
        if (isset($params['ErrorLogSearch']['start_date']) && !empty($params['ErrorLogSearch']['start_date'])) {
            $query->andWhere(" AddDate>=:start_date", [':start_date' => $params['ErrorLogSearch']['start_date']]);
        }
        if (isset($params['ErrorLogSearch']['end_date']) && !empty($params['ErrorLogSearch']['end_date'])) {
            $query->andWhere(" AddDate<=:end_date", [':end_date' => $params['ErrorLogSearch']['end_date']]);
        }
        if (isset($params['ErrorLogSearch']['type']) && !empty($params['ErrorLogSearch']['type'])) {
            $query->andWhere(" ApplicationId=:type", [':type' => $params['ErrorLogSearch']['type']]);
        }
        if (isset($params['ErrorLogSearch']['Parameter']) && !empty($params['ErrorLogSearch']['Parameter'])) {
            $query->andWhere(['like', 'Parameter', $params['ErrorLogSearch']['Parameter']]);
        }
        if (isset($params['ErrorLogSearch']['Method']) && !empty($params['ErrorLogSearch']['Method'])) {
            $query->andWhere(['like', 'Method', $params['ErrorLogSearch']['Method']]);
        }

        return $dataProvider;
    }

}

?>
