<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ApplicateName".
 *
 * @property string $id
 * @property string $appname
 * @property string $newname
 * @property integer $logtotal
 * @property integer $logtype
 */
class ApplicateName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ApplicateName';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['logtotal', 'logtype'], 'integer'],
            [['appname'], 'string', 'max' => 60],
            [['newname'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'appname' => 'Appname',
            'newname' => 'Newname',
            'logtotal' => 'Logtotal',
            'logtype' => 'Logtype',
        ];
    }
}
