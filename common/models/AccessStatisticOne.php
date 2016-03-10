<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessStatisticOne".
 *
 * @property integer $Id
 * @property string $CheckTime
 * @property string $TopType
 * @property string $DetailType1
 * @property string $DetailType2
 * @property double $Amount
 */
class AccessStatisticOne extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessStatisticOne';
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
            [['DetailType1', 'DetailType2'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'CheckTime' => '统计的周期时间，例如每10分钟统计，2016-03-02 12:10:00,',
            'TopType' => '类型，例如 地址,设备',
            'DetailType1' => '类型1级描述',
            'DetailType2' => '类型2级描述',
            'Amount' => '数量',
        ];
    }
}
