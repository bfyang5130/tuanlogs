<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TableFit".
 *
 * @property string $id
 * @property string $table_cn
 * @property string $table_en
 * @property integer $database_id
 * @property string $table_addtime
 */
class TableFit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TableFit';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['table_cn', 'table_en'], 'required'],
            [['database_id'], 'integer'],
            [['table_addtime'], 'safe'],
            [['table_cn', 'table_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'table_cn' => '表的中文名',
            'table_en' => '表的英文名',
            'database_id' => '对应的数据库ID',
            'table_addtime' => '添加时间',
        ];
    }
}
