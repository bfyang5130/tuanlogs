<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "MemoryStatus".
 *
 * @property string $id
 * @property integer $memory_total
 * @property integer $memory_cache
 * @property integer $memory_use
 * @property integer $memory_free
 * @property double $cup_percent
 * @property string $system_comefrom
 * @property string $log_time
 */
class MemoryStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'MemoryStatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['memory_total', 'memory_cache', 'memory_use', 'memory_free'], 'integer'],
            [['cup_percent'], 'number'],
            [['log_time'], 'safe'],
            [['system_comefrom'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'memory_total' => '总内存',
            'memory_cache' => '缓冲内存',
            'memory_use' => '可用内存',
            'memory_free' => '空闲内存',
            'cup_percent' => 'CPU使用率',
            'system_comefrom' => 'System Comefrom',
            'log_time' => '时间',
        ];
    }
}
