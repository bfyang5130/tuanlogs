<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "SqlAttack".
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
 * @property string $querymd5
 */
class SqlAttack extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SqlAttack';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['executedate', 'adddate'], 'safe'],
            [['sqlusedtime'], 'number'],
            [['begindate', 'enddate', 'databasetype'], 'string', 'max' => 50],
            [['sqltext'], 'string', 'max' => 20000],
            [['ip', 'querymd5'], 'string', 'max' => 64]
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
            'querymd5' => '唯一标识',
        ];
    }
}
