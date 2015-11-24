<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TraceLog".
 *
 * @property string $Id
 * @property integer $ApplicationId
 * @property string $ApplicationName
 * @property string $Method
 * @property string $Parameter
 * @property string $Content
 * @property string $AddDate
 */
class TraceLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TraceLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id'], 'required'],
            [['ApplicationId'], 'integer'],
            [['Content'], 'string'],
            [['AddDate'], 'safe'],
            [['Id', 'ApplicationName'], 'string', 'max' => 64],
            [['Method'], 'string', 'max' => 128],
            [['Parameter'], 'string', 'max' => 1024]
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
            'ApplicationName' => 'Application Name',
            'Method' => 'Method',
            'Parameter' => 'Parameter',
            'Content' => 'Content',
            'AddDate' => 'Add Date',
        ];
    }
}
