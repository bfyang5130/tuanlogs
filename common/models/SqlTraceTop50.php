<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlTrace_top50".
 *
 * @property string $sqltext
 * @property string $querymd5
 * @property string $amount
 * @property double $queryusemaxtime
 * @property string $databasetype
 * @property string $sqlquerytime
 * @property string $ip
 */
class SqlTraceTop50 extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SqlTrace_top50';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['querymd5', 'sqlquerytime'], 'required'],
            [['amount'], 'integer'],
            [['queryusemaxtime'], 'number'],
            [['sqlquerytime'], 'safe'],
            [['sqltext'], 'string', 'max' => 20000],
            [['querymd5', 'ip'], 'string', 'max' => 64],
            [['databasetype'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sqltext' => '查询语句',
            'querymd5' => '唯一标识md5',
            'amount' => '查询次数',
            'queryusemaxtime' => '语句查询最多扫行时间',
            'databasetype' => '数据库类型',
            'sqlquerytime' => '查询时间',
            'ip' => '应用服务器IP',
        ];
    }
}
