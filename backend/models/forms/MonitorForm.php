<?php

namespace backend\models\forms;

use common\models\Monitor;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class MonitorForm extends Model {

    public $monitor_name;
    public $monitor_host;
    public $monitor_item;
    public $monitor_times;

    public function save() {
        $databaseType = new Monitor();
        $databaseType->monitor_name = $this->monitor_name;
        $databaseType->monitor_host = $this->monitor_host;
        $databaseType->monitor_item = $this->monitor_item;
        $databaseType->monitor_times = $this->monitor_times;
        $databaseType->is_index=0;
        return $databaseType->save();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['monitor_name', 'monitor_host', 'monitor_item','monitor_times'], 'filter', 'filter' => 'trim'],
            [['monitor_name', 'monitor_host', 'monitor_item','monitor_times'], 'required', 'message' => '{attribute}不能空'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'monitor_name' => '监控名称',
            'monitor_host' => '监控主机',
            'monitor_item' => '监控ID',
            'monitor_times'=>'时间间隔',
        ];
    }

    /**
     * 获得监控项目的列表
     */
    public static function findItems() {
        $stt = Monitor::find()->select('id,monitor_name')->indexBy("id")->asArray(TRUE)->all();
        $returnArr = [];
        foreach ($stt as $key => $value) {
            $returnArr[$key] = $value['monitor_name'];
        }
        return $returnArr;
    }

}
