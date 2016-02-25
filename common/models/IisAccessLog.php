<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "IisAccessLog".
 *
 * @property integer $id
 * @property string $RequestTime
 * @property string $ServerIp
 * @property string $RequestType
 * @property string $CsUriStem
 * @property string $CsUriQuery
 * @property integer $ServerPort
 * @property string $CsUsername
 * @property string $ClientIp
 * @property string $UserAgent
 * @property string $System
 * @property string $Browser
 * @property integer $Status
 * @property integer $SubStatus
 * @property integer $ScWin32Status
 * @property integer $TimeTaken
 */
class IisAccessLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'IisAccessLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RequestTime'], 'safe'],
            [['ServerPort', 'Status', 'SubStatus', 'ScWin32Status', 'TimeTaken'], 'integer'],
            [['ServerIp', 'ClientIp'], 'string', 'max' => 50],
            [['RequestType'], 'string', 'max' => 200],
            [['CsUsername','CsUriStem'], 'string', 'max' => 255],
            [['UserAgent','CsUriQuery'], 'string', 'max' => 1024],
            [['System', 'Browser'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'RequestTime' => '请求时间',
            'ServerIp' => '服务器IP',
            'RequestType' => '请求方式',
            'CsUriStem' => '访问的资源',
            'CsUriQuery' => '具体的访问参数',
            'ServerPort' => '客户端连接到的端口号',
            'CsUsername' => '访问服务器的已验证用户的名称这不包括连字符 -表示的匿名用户',
            'ClientIp' => '客户端IP',
            'UserAgent' => '客户端类型',
            'System' => '客户端类型',
            'Browser' => '浏览器',
            'Status' => '状态',
            'SubStatus' => '协议子状态',
            'ScWin32Status' => 'Win32状态',
            'TimeTaken' => '操作花费的时间长短',
        ];
    }
}
