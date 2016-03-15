<?php
return [
    'adminEmail' => 'admin@example.com',
    'proxy17'=>Yii::getAlias("@backend")."/resource17",
    'proxy21'=>"F:/tudailogbase/21_nginx_proxy/21_nginx_proxy",
    'iscdn'=>[
            'app.tuandai.com',
            'www.tuandai.com',
            'hd.tuandai.com',
            'vip.tuandai.com',
            'm.tuandai.com',
        ],
    'startdate'=>'20160222',
    'cdn_parse'=>'/(.*?)[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/' ,
    'not_cdn_parse'=>'/(.*?)[\[](.*?)[\]]\s[\"](.*?)[\"]\s(\d{1,})\s(\d{1,})\s[\"](.*?)[\"]\s[\"](.*?)[\"].*?/',
    'special_parse_tag'=>'^' ,
    'domestic'=>['吉林','上海','云南','内蒙古','北京','台湾','四川','天津','宁夏','安徽','山东','山西','广东','广西','新疆','江苏','江西','河北','河南','浙江','海南','湖北','湖南','澳门','甘肃','福建','西藏','贵州','辽宁','重庆','陕西','青海','香港','黑龙江'] ,
];
