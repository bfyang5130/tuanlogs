<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessLog".
 *
 * @property integer $Id
 * @property string $UserIP1
 * @property string $UserIP2
 * @property string $UserIP3
 * @property string $UserIP4
 * @property string $RequestTime
 * @property string $RequestType
 * @property string $Protocol
 * @property string $AccessAddress
 * @property integer $Status
 * @property integer $ContentSize
 * @property string $HttpReferer
 * @property string $ClientType
 * @property string $System
 * @property string $Browser
 * @property double $TakeTime
 */
class AccessLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['RequestTime'], 'safe'],
            [['AccessAddress', 'HttpReferer'], 'string'],
            [['Status', 'ContentSize'], 'integer'],
            [['TakeTime'], 'number'],
            [['UserIP1', 'UserIP2', 'UserIP3', 'UserIP4'], 'string', 'max' => 20],
            [['RequestType', 'Protocol'], 'string', 'max' => 200],
            [['ClientType'], 'string', 'max' => 1024],
            [['System', 'Browser'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'UserIP1' => '用户IP1',
            'UserIP2' => '用户IP2',
            'UserIP3' => '用户IP3',
            'UserIP4' => '用户IP4',
            'RequestTime' => '请求时间',
            'RequestType' => '请求方式',
            'Protocol' => '协议',
            'AccessAddress' => '请求地址',
            'Status' => '状态',
            'ContentSize' => '内容大小',
            'HttpReferer' => '入口地址',
            'ClientType' => '客户端类型',
            'System' => '操作系统',
            'Browser' => '浏览器',
            'TakeTime' => '耗时',
        ];
    }
}
