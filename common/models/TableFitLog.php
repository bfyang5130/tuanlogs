<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "TableFitLog".
 *
 * @property string $id
 * @property integer $database_id
 * @property integer $tablefit_id
 * @property string $database_en
 * @property string $table_en
 * @property integer $Number
 * @property double $totoltime
 * @property string $Date
 * @property string $Updatetime
 */
class TableFitLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TableFitLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['database_id', 'tablefit_id', 'database_en', 'table_en'], 'required'],
            [['database_id', 'tablefit_id', 'Number'], 'integer'],
            [['totoltime'], 'number'],
            [['Date', 'Updatetime'], 'safe'],
            [['database_en', 'table_en'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'database_id' => '数据库ID',
            'tablefit_id' => '表名ID',
            'database_en' => 'Database En',
            'table_en' => 'Table En',
            'Number' => '访问数量',
            'totoltime' => '耗时',
            'Date' => '统计时间',
            'Updatetime' => '更新时间',
        ];
    }
}
