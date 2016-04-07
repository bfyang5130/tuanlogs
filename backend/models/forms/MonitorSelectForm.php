<?php

namespace backend\models\forms;

use common\models\Monitor;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class MonitorSelectForm extends Model {

    public $selectid;
    public $stime;
    public $etime;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['selectid', 'stime', 'etime'], 'filter', 'filter' => 'trim'],
            [['selectid', 'stime', 'etime'], 'required', 'message' => '{attribute}不能空'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'selectid' => '监控名称',
            'stime' => '开始时间',
            'etime' => '结束时间',
        ];
    }

}
