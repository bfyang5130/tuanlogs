<?php

namespace backend\models\forms;

use common\models\SqlTrace;
use common\models\DatabaseType;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class DataBaseTypeForm extends Model {

    public $database_cn;
    public $database_en;

    /**
     * 处理数据库是否正确
     */
    public function testDabaseEn() {
        #判断是否已经存在该库
        $countone = DatabaseType::find()->where("database_en=:baseen", [':baseen' => $this->database_en])->count();
        if ($countone > 0) {
            $this->addError('database_en', '当前库已经增加！');
        }
        $countone = SqlTrace::find()->where("databasetype=:baseen", [':baseen' => $this->database_en])->count();
        if ($countone == 0) {
            $this->addError('database_en', '不存在该库的日志记录！');
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['database_cn', 'database_en'], 'filter', 'filter' => 'trim'],
            [['database_cn', 'database_en'], 'required', 'message' => '{attribute}不能空'],
            ['database_en', 'testDabaseEn'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'database_cn' => '数据库中文名',
            'database_en' => '数据库英文名',
        ];
    }

}
