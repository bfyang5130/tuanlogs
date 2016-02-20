<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TraceLog_day".
 *
 * @property integer $Id
 * @property string $ApplicationId
 * @property integer $Number
 * @property integer $Date
 * @property integer $Updatetime
 */
class TraceLogDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TraceLog_day';
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
            'Id' => 'ID',
            'ApplicationId' => 'Application ID',
            'Number' => 'Number',
            'Date' => 'Date',
            'Updatetime' => 'Updatetime',
        ];
    }
}
