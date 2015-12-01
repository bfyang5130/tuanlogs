<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "logtype".
 *
 * @property string $id
 * @property string $type_name
 * @property string $type_cn_name
 */
class LogType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'logtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type_name', 'type_cn_name'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type_name' => 'Type Name',
            'type_cn_name' => 'Type Cn Name',
        ];
    }
}
