<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TraceLog".
 *
 * @property string $Id
 * @property string $ApplicationId
 * @property string $Method
 * @property string $Parameter
 * @property string $Content
 * @property string $AddDate
 * @property string $IP
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
            [['Content'], 'string'],
            [['AddDate'], 'safe'],
            [['Id'], 'string', 'max' => 36],
            [['ApplicationId', 'IP'], 'string', 'max' => 64],
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
            'Id' => '主键',
            'ApplicationId' => '应用Id',
            'Method' => '方法名称',
            'Parameter' => '方法参数',
            'Content' => '跟踪日志内容',
            'AddDate' => '新增日期',
            'IP' => '应用服务器IP',
        ];
    }
}
