<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ErrorLog".
 *
 * @property string $Id
 * @property integer $ApplicationId
 * @property string $ApplicationName
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
            [['ApplicationId', 'ErrorStatus'], 'integer'],
            [['Content', 'HandlerResult'], 'string'],
            [['AllocationDate', 'HandlerDate', 'AuditDate', 'AddDate'], 'safe'],
            [['Id', 'ApplicationName'], 'string', 'max' => 64],
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
            'Id' => 'ID',
            'ApplicationId' => 'Application ID',
            'ApplicationName' => 'Application Name',
            'Method' => 'Method',
            'Parameter' => 'Parameter',
            'Content' => 'Content',
            'AllocationDate' => 'Allocation Date',
            'AllocationUserId' => 'Allocation User ID',
            'HandlerDate' => 'Handler Date',
            'HandlerUserId' => 'Handler User ID',
            'HandlerResult' => 'Handler Result',
            'AuditDate' => 'Audit Date',
            'AuditUserId' => 'Audit User ID',
            'AuditResult' => 'Audit Result',
            'ErrorStatus' => 'Error Status',
            'AddDate' => 'Add Date',
        ];
    }
}
