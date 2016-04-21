<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TargetSourceUrl".
 *
 * @property string $id
 * @property string $target_url
 * @property string $from_url
 * @property integer $nums
 * @property string $vist_time
 */
class TargetSourceUrl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TargetSourceUrl';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nums'], 'integer'],
            [['vist_time'], 'safe'],
            [['target_url', 'from_url'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'target_url' => '目标地址',
            'from_url' => '来源地址',
            'nums' => '访问数量',
            'vist_time' => '时间段',
        ];
    }
}
