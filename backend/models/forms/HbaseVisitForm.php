<?php

namespace backend\models\forms;

use common\models\Monitor;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class HbaseVisitForm extends Model {

    public $start_time;
    public $end_time;
    public $web_visit;
    public $proxy;
    public $show_check;
    public $search_col;
    public $key_word;

    public function save() {
        
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['start_time', 'end_time', 'web_visit', 'proxy', 'search_col', 'key_word', 'show_check'], 'filter', 'filter' => 'trim'],
            [['start_time', 'end_time', 'web_visit', 'proxy', 'search_col', 'show_check'], 'required', 'message' => '{attribute}不能空'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'start_time' => '开始时间',
            'end_time' => '结束时间',
            'web_visit' => '访问网站',
            'proxy' => '代理类型',
            'search_col' => '查询列名',
            'show_check' => '显示的数据',
            'key_word' => '关键字',
        ];
    }

    /**
     * 定义一个网址访问的列表
     */
    public static function webVisit() {
        return [
            '0001' => 'www.tuandai.com',
            '0002' => 'hd.tuandai.com',
            '0003' => 'app.tuandai.com',
            '0004' => 'm.tuandai.com',
            '0005' => 'vip.tuandai.com'
        ];
    }

    /**
     * 返回代理数据
     * @return type
     */
    public static function proxyList() {
        return [
            'nginx' => 'nginx',
            'iis' => 'iis'
        ];
    }

    /**
     * 返回需要显示的字段
     * 
     */
    public static function showlist() {
        return [
            'ip' => 'IP',
            'country' => '国家',
            'province' => '省份',
            'city' => '地区',
            'datereg' => '访问日期',
            'request_url' => '请求地址',
            'request_method' => '请求方式',
            'request_protocol' => '协议类型',
            'statuscode' => '状态',
            'bodysize' => '文件大小',
            'fromurl' => '来源地址',
            'agent' => '客户端信息',
            'plat' => '平台',
            'bower' => '浏览器',
            'mobile_plat' => '手机平台',
            'requestTime' => '耗时'
        ];
    }

    /**
     * 返回查询的列名
     */
    public static function colLists() {
        return [
            'ip' => 'IP',
            'country' => '国家',
            'province' => '省份',
            'city' => '地区',
            'datereg' => '访问日期',
            'request_url' => '请求地址',
            'statuscode' => '状态',
            'bodysize' => '文件大小',
            'fromurl' => '来源地址',
            'agent' => '客户端信息',
            'plat' => '平台',
            'bower' => '浏览器',
            'mobile_plat' => '手机平台',
            'requestTime' => '耗时'
        ];
    }

}
