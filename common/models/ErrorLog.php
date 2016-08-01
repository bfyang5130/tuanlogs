<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ErrorLog".
 *
 * @property string $Id
 * @property string $ApplicationId
 * @property string $Method
 * @property string $Parameter
 * @property string $Content
 * @property string $AllocationDate
 * @property string $AllocationUserId
 * @property string $HandlerDate
 * @property string $HandlerUserId
 * @property string $HandlerResult
 * @property string $AuditDate
 * @property string $AuditUserId
 * @property string $AuditResult
 * @property integer $ErrorStatus
 * @property string $AddDate
 * @property string $IP
 */
class ErrorLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ErrorLog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Id'], 'required'],
            [['Content', 'HandlerResult'], 'string'],
            [['AllocationDate', 'HandlerDate', 'AuditDate', 'AddDate'], 'safe'],
            [['ErrorStatus'], 'integer'],
            [['Id'], 'string', 'max' => 36],
            [['ApplicationId', 'IP'], 'string', 'max' => 64],
            [['Method', 'Parameter'], 'string', 'max' => 1024],
            [['AllocationUserId', 'HandlerUserId', 'AuditUserId', 'AuditResult'], 'string', 'max' => 128]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'Id' => '主键',
            'ApplicationId' => '应用Id',
            'Method' => '方法名称',
            'Parameter' => '方法参数',
            'Content' => '错误内容',
            'AllocationDate' => '分配日期',
            'AllocationUserId' => '分配人员',
            'HandlerDate' => '处理时间',
            'HandlerUserId' => '处理人员',
            'HandlerResult' => '处理结果',
            'AuditDate' => '审核日期',
            'AuditUserId' => '审核人员',
            'AuditResult' => '审核结果',
            'ErrorStatus' => '错误状态',
            'AddDate' => '新增时间',
            'IP' => '应用服务器IP',
        ];
    }
}
