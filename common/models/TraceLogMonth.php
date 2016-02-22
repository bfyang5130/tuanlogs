<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TraceLog_month".
 *
 * @property integer $Id
 * @property string $ApplicationId
 * @property integer $Number
 * @property string $Month
 * @property string $Updatetime
 */
class TraceLogMonth extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TraceLog_month';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Number'], 'integer'],
            [['Updatetime'], 'safe'],
            [['ApplicationId'], 'string', 'max' => 64],
            [['Month'], 'string', 'max' => 11]
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
            'Month' => 'Month',
            'Updatetime' => 'Updatetime',
        ];
    }
}
