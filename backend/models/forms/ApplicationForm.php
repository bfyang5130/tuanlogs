<?php

namespace backend\models\forms;

use common\models\ApplicateName;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ApplicationForm extends Model {

    public $appname;
    public $logtype;

    public function save() {
        $databaseType = new ApplicateName();
        $databaseType->appname = $this->appname;
        $databaseType->logtype = $this->logtype;
        $databaseType->logtotal = 0;
        $databaseType->newname = $this->appname;
        $databaseType->lastupdatetime = date('Y-m-d H:i:s');
        return $databaseType->save();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['appname', 'filter', 'filter' => 'trim'],
            ['appname', 'required', 'message' => '{attribute}不能空'],
            ['logtype','in','range'=>['0','1'],'message' => '错误的类型'],
            ['appname', 'testAppname', 'message' => '该类型已经存在']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'appname' => '类型标记',
            'logtype' => '日志类型',
        ];
    }

    /**
     * 检查日志类型是否唯一
     */
    public function testAppname() {
        $stt = ApplicateName::find()->where('appname=:appname AND logtype=:logtype', [':appname' => $this->appname, ':logtype' => $this->logtype])->count(1);
        if ($stt > 0) {
            $this->addError('appname', '该类型已经存在');
        }
    }

    /**
     * 获得日志的类型
     */
    public static function getType() {
        return[
            '错误日志' => 0,
            '跟踪日志' => 1
        ];
    }

}
