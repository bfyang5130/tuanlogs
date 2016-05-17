<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Monitor_log".
 *
 * @property string $id
 * @property integer $monitor_id
 * @property string $serize_string
 * @property string $log_date
 */
class MonitorLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Monitor_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['monitor_id'], 'required'],
            [['monitor_id'], 'integer'],
            [['log_date'], 'safe'],
            [['serize_string'], 'string', 'max' => 1000]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'monitor_id' => '监控id',
            'serize_string' => '序列化的数据串',
            'log_date' => '记录时间',
        ];
    }
}
