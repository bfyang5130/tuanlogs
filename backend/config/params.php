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
];
