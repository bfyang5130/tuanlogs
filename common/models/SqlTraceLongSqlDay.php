<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlTrace_LongSqlDay".
 *
 * @property integer $Id
 * @property string $databasetype
 * @property integer $Amount
 * @property double $TotalSqlusedtime
 * @property string $StatisticDate
 * @property string $Updatetime
 */
class SqlTraceLongSqlDay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SqlTrace_LongSqlDay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Amount'], 'integer'],
            [['TotalSqlusedtime'], 'number'],
            [['StatisticDate', 'Updatetime'], 'safe'],
            [['databasetype'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => '主键',
            'databasetype' => '数据库',
            'Amount' => '查询次数',
            'TotalSqlusedtime' => '查询总时间',
            'StatisticDate' => '统计日期',
            'Updatetime' => '更新时间',
        ];
    }
}
