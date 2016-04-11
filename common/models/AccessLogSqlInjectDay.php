<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessLogSqlInject_Day".
 *
 * @property integer $Id
 * @property integer $Amount
 * @property string $StatisticDate
 * @property string $Updatetime
 */
class AccessLogSqlInjectDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessLogSqlInject_Day';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Amount'], 'integer'],
            [['StatisticDate', 'Updatetime'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => '主键',
            'Amount' => '数量',
            'StatisticDate' => '统计日期',
            'Updatetime' => '更新时间',
        ];
    }
}
