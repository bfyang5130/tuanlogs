<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessLog_Iismost".
 *
 * @property integer $Id
 * @property string $AccessIP
 * @property integer $AccessIPNum
 * @property string $Most_Address
 * @property integer $Most_AddressNum
 * @property string $Website
 * @property string $server
 * @property string $Date_time
 */
class AccessLogIismost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessLog_Iismost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['AccessIPNum', 'Most_AddressNum'], 'integer'],
            [['Most_Address'], 'string'],
            [['Date_time'], 'safe'],
            [['AccessIP'], 'string', 'max' => 20],
            [['Website', 'server'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'AccessIP' => '访问IP',
            'AccessIPNum' => '访问IP数',
            'Most_Address' => '最多访问地址',
            'Most_AddressNum' => '最多访问地址访问数',
            'Website' => '所访问的站点',
            'server' => '访问的服务器',
            'Date_time' => '时间',
        ];
    }
}
