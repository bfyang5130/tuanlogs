<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ErrorLog_month".
 *
 * @property integer $Id
 * @property string $ApplicationId
 * @property integer $Number
 * @property integer $Month
 * @property integer $Updatetime
 */
class ErrorLogMonth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ErrorLog_month';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Number', 'Month', 'Updatetime'], 'integer'],
            [['ApplicationId'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => '主键',
            'ApplicationId' => '应用Id',
            'Number' => '数量',
            'Month' => '统计月份',
            'Updatetime' => '更新时间',
        ];
    }
}
