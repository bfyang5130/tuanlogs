<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "IisAccessLog".
 *
 * @property integer $Id
 * @property string $Ip1
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $date_reg
 * @property string $request_method
 * @property string $request_url
 * @property string $request_protocol
 * @property integer $status_code
 * @property integer $body_size
 * @property string $from_url
 * @property string $agent
 * @property string $plat
 * @property string $bower
 * @property string $mobile_plat
 * @property double $request_time
 * @property string $visitwebsite
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
            [['date_reg'], 'safe'],
            [['request_url', 'from_url', 'agent'], 'string'],
            [['status_code', 'body_size'], 'integer'],
            [['request_time'], 'number'],
            [['Ip1', 'province', 'city', 'request_method'], 'string', 'max' => 20],
            [['country', 'visitwebsite'], 'string', 'max' => 255],
            [['request_protocol'], 'string', 'max' => 100],
            [['plat', 'bower', 'mobile_plat'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => 'ID',
            'Ip1' => '用户IP1',
            'country' => '国家',
            'province' => '省份',
            'city' => '城市',
            'date_reg' => '请求时间',
            'request_method' => '请求方法',
            'request_url' => '请求地址',
            'request_protocol' => '请求协议',
            'status_code' => '状态',
            'body_size' => '内容大小',
            'from_url' => '入口地址',
            'agent' => '信息',
            'plat' => '平台',
            'bower' => '浏览器',
            'mobile_plat' => '手机信息',
            'request_time' => '耗时',
            'visitwebsite' => '访问的网址',
        ];
    }
}
