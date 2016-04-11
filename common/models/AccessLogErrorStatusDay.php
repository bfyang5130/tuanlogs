<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessLogErrorStatus_Day".
 *
 * @property integer $Id
 * @property integer $error_status
 * @property integer $Amount
 * @property string $StatisticDate
 * @property string $Updatetime
 */
class AccessLogErrorStatusDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessLogErrorStatus_Day';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['error_status', 'Amount'], 'integer'],
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
            'error_status' => '错误状态',
            'Amount' => '数量',
            'StatisticDate' => '统计日期',
            'Updatetime' => '更新时间',
        ];
    }
}
