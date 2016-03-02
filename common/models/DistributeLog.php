<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "distribute_log".
 *
 * @property integer $id
 * @property integer $start_num
 * @property integer $end_num
 * @property string $start_time
 * @property string $end_time
 * @property integer $statis
 * @property string $file
 * @property string $target
 * @property string $source
 */
class DistributeLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'distribute_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'start_num', 'end_num', 'statis'], 'integer'],
            [['start_time', 'end_time'], 'safe'],
            [['file', 'source'], 'string', 'max' => 100],
            [['target'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'start_num' => 'Start Num',
            'end_num' => 'End Num',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'statis' => 'Statis',
            'file' => 'File',
            'target' => 'Target',
            'source' => 'Source',
        ];
    }
}
