<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "Monitor".
 *
 * @property string $id
 * @property string $monitor_name
 * @property string $monitor_host
 * @property integer $monitor_item
 * @property integer $monitor_times
 * @property integer $is_index
 */
class Monitor extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Monitor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['monitor_item', 'monitor_times', 'is_index'], 'integer'],
            [['monitor_name', 'monitor_host'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'monitor_name' => '监控名称',
            'monitor_host' => '监控的主要地址',
            'monitor_item' => '监控的项目ID',
            'monitor_times' => '时间间隔',
            'is_index' => '是否首页显示',
        ];
    }
}
