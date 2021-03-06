<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlTrace".
 *
 * @property string $begindate
 * @property string $enddate
 * @property string $executedate
 * @property string $adddate
 * @property string $sqltext
 * @property double $sqlusedtime
 * @property string $Id
 * @property string $databasetype
 * @property string $ip
 */
class SqlTrace extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SqlTrace';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['executedate', 'adddate'], 'safe'],
            [['sqlusedtime'], 'number'],
            [['begindate', 'enddate'], 'string', 'max' => 50],
            [['sqltext'], 'string', 'max' => 5000],
            [['databasetype'], 'string', 'max' => 20],
            [['ip'], 'string', 'max' => 64]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'begindate' => 'Begindate',
            'enddate' => 'Enddate',
            'executedate' => 'Executedate',
            'adddate' => 'Adddate',
            'sqltext' => 'Sqltext',
            'sqlusedtime' => 'Sqlusedtime',
            'Id' => 'ID',
            'databasetype' => 'Databasetype',
            'ip' => '应用服务器IP',
        ];
    }
}
