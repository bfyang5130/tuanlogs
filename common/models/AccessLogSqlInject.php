<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessLogSqlInject".
 *
 * @property integer $id
 * @property string $user_ip
 * @property string $access_str
 * @property string $request_time
 * @property string $source
 * @property string $log_type
 */
class AccessLogSqlInject extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessLogSqlInject';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['access_str'], 'string'],
            [['request_time'], 'safe'],
            [['user_ip'], 'string', 'max' => 100],
            [['source'], 'string', 'max' => 50],
            [['log_type'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_ip' => '用户IP',
            'access_str' => '错误状态',
            'request_time' => ' 添加时间',
            'source' => ' 来源 17,23',
            'log_type' => ' 日志类型',
        ];
    }
}
