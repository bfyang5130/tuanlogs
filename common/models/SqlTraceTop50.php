<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlTrace_top50".
 *
 * @property string $executedate
 * @property string $sqltext_md5
 * @property double $sqlusedtime
 * @property string $sqltext
 * @property string $Id
 * @property string $databasetype
 * @property string $update_time
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
            [['executedate', 'update_time'], 'safe'],
            [['sqlusedtime'], 'number'],
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
            'executedate' => 'Executedate',
            'sqltext_md5' => 'Sqltext Md5',
            'sqlusedtime' => 'Sqlusedtime',
            'sqltext' => 'Sqltext',
            'Id' => 'ID',
            'databasetype' => 'Databasetype',
            'update_time' => 'Update Time',
        ];
    }
}
