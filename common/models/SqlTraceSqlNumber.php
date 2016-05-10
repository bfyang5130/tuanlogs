<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlTrace_SqlNumber".
 *
 * @property string $sqltext_md5
 * @property integer $Amount
 * @property string $sqltext
 * @property string $Id
 * @property string $databasetype
 * @property string $update_time
 */
class SqlTraceSqlNumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SqlTrace_SqlNumber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Amount'], 'integer'],
            [['update_time'], 'safe'],
            [['sqltext_md5'], 'string', 'max' => 64],
            [['sqltext'], 'string', 'max' => 5000],
            [['databasetype'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sqltext_md5' => 'Sqltext Md5',
            'Amount' => '次数',
            'sqltext' => 'sql语句',
            'Id' => 'ID',
            'databasetype' => '数据库',
            'update_time' => '统计时间',
        ];
    }
}
