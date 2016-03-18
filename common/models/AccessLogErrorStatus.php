<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "AccessLogErrorStatus".
 *
 * @property integer $id
 * @property integer $error_status
 * @property string $request_url
 * @property string $user_ip
 * @property string $request_time
 * @property string $add_time
 * @property string $source
 * @property string $log_type
 */
class AccessLogErrorStatus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'AccessLogErrorStatus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['error_status'], 'integer'],
            [['request_url','source','log_type'], 'string'],
            [['request_time', 'add_time'], 'safe'],
            [['user_ip'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'error_status' => '错误状态',
            'request_url' => ' 访问url',
            'user_ip' => '用户ip',
            'request_time' => ' 访问时间',
            'add_time' => ' 添加时间',
            'source' => ' 来源',
            'log_type' => ' 日志类型',
        ];
    }
}
