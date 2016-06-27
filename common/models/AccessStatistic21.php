<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessStatistic21".
 *
 * @property integer $Id
 * @property string $CheckTime
 * @property string $TopType
 * @property string $DetailType1
 * @property string $DetailType2
 * @property string $DetailType3
 * @property double $Amount
 * @property string $LogType
 */
class AccessStatistic21 extends \yii\db\ActiveRecord
{
    public static function getDb() {
        return \Yii::$app->db1;
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessStatistic21';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['CheckTime'], 'safe'],
            [['Amount'], 'number'],
            [['TopType'], 'string', 'max' => 32],
            [['DetailType1', 'DetailType2', 'DetailType3', 'LogType'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'CheckTime' => '统计的周期时间,',
            'TopType' => '类型，例如 地址,设备',
            'DetailType1' => '类型1级描述',
            'DetailType2' => '类型2级描述',
            'DetailType3' => '类型3级描述',
            'Amount' => '数量',
            'LogType' => 'Log Type',
        ];
    }
}
