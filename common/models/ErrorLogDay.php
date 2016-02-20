<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ErrorLog_day".
 *
 * @property integer $Id
 * @property string $ApplicationId
 * @property integer $Number
 * @property integer $Date
 * @property integer $Updatetime
 */
class ErrorLogDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ErrorLog_day';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Number', 'Date', 'Updatetime'], 'integer'],
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
            'Date' => '统计日期',
            'Updatetime' => '更新时间',
        ];
    }
}
