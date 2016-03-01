<?php

namespace backend\models\forms;

use common\models\SqlTrace;
use common\models\TableFit;
use common\models\DatabaseType;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class TableFitForm extends Model {

    public $table_cn;
    public $table_en;
    public $database_id;

    /**
     * 获得统计的数据库类型列表
     * @return type
     */
    public static function findDatabase() {
        $databaseLists = DatabaseType::find()
                ->select(['id', 'database_en'])
                ->indexBy('id')
                ->asArray()
                ->all();
        $fiArray = [];
        if ($databaseLists) {
            foreach ($databaseLists as $key => $value) {
                $fitArray[$key] = $value['database_en'];
            }
        }
        return $fitArray;
    }

    /**
     * 保存表数据
     * @return type
     */
    public function save() {
        $newtable = new TableFit();
        $newtable->table_en = $this->table_en;
        $newtable->table_cn = $this->table_cn;
        $newtable->database_id = $this->database_id;
        $newtable->table_addtime = date("Y-m-d H:i:s");
        return $newtable->save();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['table_cn', 'table_en'], 'filter', 'filter' => 'trim'],
            [['table_cn', 'table_en', 'database_id'], 'required', 'message' => '{attribute}不能空'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'table_cn' => '表中文名',
            'table_en' => '表英文名',
            'database_id' => '所属数据库',
        ];
    }

}
