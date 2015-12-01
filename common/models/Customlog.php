<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customlog".
 *
 * @property string $id
 * @property integer $logtype_id
 * @property string $call_methods
 * @property string $errormsg
 * @property string $call_parameter
 * @property integer $add_time
 * @property integer $fit_time
 */
class Customlog extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'customlog';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['logtype_id', 'add_time', 'fit_time'], 'integer'],
            [['errormsg'], 'string'],
            [['call_methods', 'call_parameter'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'logtype_id' => 'Logtype ID',
            'call_methods' => 'Call Methods',
            'errormsg' => 'Errormsg',
            'call_parameter' => 'Call Parameter',
            'add_time' => 'Add Time',
            'fit_time' => 'Fit Time',
        ];
    }

    public function beforeSave($insert) {
        if ($this->isNewRecord) {
            $this->add_time = time();
            $this->fit_time = 0;
        }
        return parent::beforeSave($insert);
    }

}
