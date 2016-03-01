<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "DatabaseType".
 *
 * @property string $id
 * @property string $database_cn
 * @property string $database_en
 * @property string $adtabase_addtime
 */
class DatabaseType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'DatabaseType';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['database_cn', 'database_en'], 'required'],
            [['adtabase_addtime'], 'safe'],
            [['database_cn', 'database_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'database_cn' => '数据库中文名',
            'database_en' => '数据库英文名',
            'adtabase_addtime' => '添加时间',
        ];
    }
}
