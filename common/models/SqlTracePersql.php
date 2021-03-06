<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlTrace_persql".
 *
 * @property string $id
 * @property string $sqltext
 * @property string $querymd5
 * @property string $amount
 * @property double $queryusemaxtime
 * @property string $databasetype
 * @property string $sqlquerytime
 * @property integer $is_new
 * @property string $ip
 */
class SqlTracePersql extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SqlTrace_persql';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sqltext'], 'string'],
            [['amount', 'is_new'], 'integer'],
            [['queryusemaxtime'], 'number'],
            [['sqlquerytime'], 'safe'],
            [['querymd5'], 'string', 'max' => 500],
            [['databasetype'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'sqltext' => '查询语句',
            'querymd5' => '唯一标识md5',
            'amount' => '查询次数',
            'queryusemaxtime' => '语句查询最多扫行时间',
            'databasetype' => '数据库类型',
            'sqlquerytime' => '查询时间',
            'is_new' => '是否为新增数据',
            'ip' => '应用服务器IP',
        ];
    }
}
