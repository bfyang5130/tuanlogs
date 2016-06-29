<?php

namespace backend\services;

use yii\helpers\Url;
use common\models\AccessLogSqlInject;
use common\models\AccessLogSqlInjectDay;
use common\models\AccessLogErrorStatusDay;
use yii\db\Query;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class NginxHightchartService {

    const AccessStatistic = 1;
    const AccessStatisticOne = 2;
    const AccessStatistic17 = 3;
    const AccessStatistic21 = 4;

    /**
     * 获得一个简单的饼状图
     * @return type
     */
    public static function getPieHightChart($date, $topType, $params, $groupstring, $tableselect, $moreClick = FALSE) {

        $dateString = NginxService::findGroupString($date, $topType, $params, $groupstring, $tableselect);
        if (empty($dateString)) {
            return [];
        }
//得到国内数据
        $fitOurCountry = \Yii::$app->params['domestic'];
        $ourCountry = [];
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            $url = Url::to('/nginx/city.html?table=' . $tableselect . '&date=' . $date . '&cityname=' . urlencode($oneDate[$groupstring]));
            if (in_array($oneDate[$groupstring], $fitOurCountry)) {
                $ourCountry['categories'][] = $oneDate[$groupstring];
                //再多一层的点击url
                if ($moreClick === true) {
                    $ourCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum']), 'url' => $url];
                } else {
                    $ourCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum'])];
                }

                $ourCountry['series']['colorByPoint'] = TRUE;
                $ourCountry['series']['name'] = '访问量';
            } else {
                $otherCountry['categories'][] = $oneDate[$groupstring];
                if ($moreClick === true) {
                    $otherCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum']), 'url' => $url];
                } else {
                    $otherCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum'])];
                }
                $otherCountry['series']['colorByPoint'] = TRUE;
                $otherCountry['series']['name'] = '访问量';
            }
        }
        return ['in_country' => $ourCountry, 'out_country' => $otherCountry];
    }

    /**
     * 获得一个简单的饼状图
     * @return type
     */
    public static function getPiePlatHightChart($date, $topType, $params, $groupstring, $tableselect, $moreClick = FALSE) {

        $dateString = NginxService::findGroupString($date, $topType, $params, $groupstring, $tableselect);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            if (trim($oneDate[$groupstring]) == '') {
                $oneDate[$groupstring] = '其他';
            }
            $otherCountry['categories'][] = $oneDate[$groupstring];
            $otherCountry['series']['data'][] = ['name' => $oneDate[$groupstring], 'y' => floatval($oneDate['totalNum'])];
            $otherCountry['series']['colorByPoint'] = TRUE;
            $otherCountry['series']['name'] = '访问量';
        }
        return ['in_country' => $otherCountry];
    }

    /**
     * 获得一个简单的线条图
     * @return type
     */
    public static function getSplinePlatHightChart($date, $topType, $params, $groupstring, $tableselect, $moreClick = FALSE) {

        $dateString = NginxService::findGroupString($date, $topType, $params, $groupstring, $tableselect);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        $otherCountry['series']['name'] = array_values($params);
        $vist24Hours = [];
        //把10分钟的数据处理成24小时数据
        foreach ($dateString as $oneDate) {
            $fittime = date('H', strtotime($oneDate[$groupstring]));
            if (!empty($vist24Hours[$fittime])) {
                $vist24Hours[$fittime]+=floatval($oneDate['totalNum']);
            } else {
                //未有的数据就放进去
                $otherCountry['categories'][] = date('H', strtotime($oneDate[$groupstring]));
                $vist24Hours[$fittime] = floatval($oneDate['totalNum']);
            }
        }
        foreach ($vist24Hours as $key => $oneDate) {
            $otherCountry['series']['data'][] = [$key, $oneDate];
        }
        return ['in_country' => $otherCountry];
    }

    /**
     * 获得首页要显示的访问情况
     */
    public static function pageAttack() {
        //获得总的攻击信息记录数
        $sqlattack = AccessLogSqlInject::find()->count();
        //获得访问出错的数据
        $query = new Query;
        $dateString = $query->select("count(*) nums,error_status")->from('AccessLogErrorStatus')->groupBy("error_status")->orderBy("nums desc")->all();
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        $otherCountry['categories'][] = '注入';
        $otherCountry['series']['data'][] = ['url' => Url::toRoute('/nginx/sqlattack'), 'name' => '注入', 'y' => floatval($sqlattack)];
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['error_status'];
            $otherCountry['series']['data'][] = ['url' => Url::toRoute('/nginx/errorstatus') . '?AccessLogErrorStatusSearch%5Berror_status%5D=' . $oneDate['error_status'], 'name' => $oneDate['error_status'], 'y' => floatval($oneDate['nums'])];
            $otherCountry['series']['name'] = '数量';
            $otherCountry['series']['color'] = 'red';
        }

        return ['in_country' => $otherCountry];
    }

    /**
     * 获得首页要显示的访问情况
     */
    public static function pageAttackEcharts() {
        //获得总的攻击信息记录数
        $sqlattack = AccessLogSqlInject::find()->count();
        //获得访问出错的数据
        $query = new Query;
        $dateString = $query->select("count(*) nums,error_status")->from('AccessLogErrorStatus')->groupBy("error_status")->orderBy("nums desc")->all(\Yii::$app->db1);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        $otherCountry['categories'][] = '注入';
        //$otherCountry['series']['data'][] = ['url' => Url::toRoute('/nginx/sqlattack'), 'name' => '注入', 'y' => floatval($sqlattack)];
        $otherCountry['series']['data'][] = floatval($sqlattack);
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['error_status'];
            //$otherCountry['series']['data'][] = ['url' => Url::toRoute('/nginx/errorstatus') . '?AccessLogErrorStatusSearch%5Berror_status%5D=' . $oneDate['error_status'], 'name' => $oneDate['error_status'], 'y' => floatval($oneDate['nums'])];
            $otherCountry['series']['data'][] = floatval($oneDate['nums']);
        }

        $otherCountry['series']['name'] = '数量';
        $otherCountry['series']['type'] = 'bar';
        $otherCountry['series']['itemStyle'] = [
            'normal' => [
                'label' => [
                    'show' => true,
                    'position' => 'top'
                ]
            ]
        ];
        return $otherCountry;
    }

    /**
     * 获得最近五天的错误状态进行展示
     * @return type
     */
    public static function findAllLine() {
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = AccessLogErrorStatusDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All();
        //如果为空，就取最后一个时间，以最后一个时间点为准
        //标记是否为最新的数据
        $isToday = true;
        if (empty($fiveDateLists)) {
            $lastDay = AccessLogErrorStatusDay::find()->orderBy("Id desc")->one();
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->StatisticDate));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = AccessLogErrorStatusDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All();

            if (empty($fiveDateLists)) {
                return [];
            }
            $isToday = false;
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['StatisticDate']));
            $fitdates[$oneDate['error_status']][$toIntDay] = floatval($oneDate['Amount']);
        }
        #对处理了的数据再处理一遍，不存在的数据加上0
        $outCharts = [];
        $outCharts['categories'] = [];
        $outCharts['series'] = [];
        foreach ($fitdates as $key => $oneDate) {
            $start_date = date('Y-m-d', strtotime($fitDate));
            for ($i = 1; $i <= 5; $i++) {
                if (!in_array($start_date, $outCharts['categories'])) {
                    $outCharts['categories'][] = $start_date;
                }
                if (!isset($oneDate[$start_date])) {
                    $fitdates[$key][$start_date] = 0;
                }
                $start_date = date('Y-m-d', strtotime("-1 day", strtotime($start_date)));
            }
            ksort($fitdates[$key]);
        }
        foreach ($fitdates as $key => $oneDate) {
            $outCharts['series'][] = [
                'name' => $key,
                'data' => array_values($oneDate),
            ];
        }
        $outCharts['categories'] = array_reverse($outCharts['categories']);
        //标记是否为今天的数据，如果是今天的数据,那么对今天的数据进行实时统计处理
        if ($isToday) {
            $fitDate = date('Y-m-d 00:00:00');

            $after1Date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($fitDate)));
            //因为上面已经做了判断，所以今天的数据肯定会存在
            //获得今天最新的数据
            $errorstatusLists = \common\models\AccessLogErrorStatus::find()->select("count(*) nums,error_status")->where('request_time>:sd AND request_time<=:ed', [':sd' => $fitDate, ':ed' => $after1Date])->groupBy('error_status')->indexBy('error_status')->asArray()->all();

            //对上面的数组进行处理
            foreach ($outCharts['series'] as $key => $oneVaue) {
                //把最近一个元素推出
                array_pop($outCharts['series'][$key]['data']);
                //推入一个新元素
                //如果没有这个数组就推入一个0的数据
                if (!isset($errorstatusLists[$oneVaue['name']]['nums'])) {
                    array_push($outCharts['series'][$key]['data'], 0);
                } else {
                    array_push($outCharts['series'][$key]['data'], floatval($errorstatusLists[$oneVaue['name']]['nums']));
                }
            }
        }
        return $outCharts;
    }

    /**
     * 获得最近五天的错误状态进行展示
     * @return type
     */
    public static function findAllLineEcharts() {
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = AccessLogErrorStatusDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All(\Yii::$app->db1);
        //如果为空，就取最后一个时间，以最后一个时间点为准
        //标记是否为最新的数据
        $isToday = true;
        if (empty($fiveDateLists)) {
            $lastDay = AccessLogErrorStatusDay::find()->orderBy("Id desc")->one(\Yii::$app->db1);
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->StatisticDate));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = AccessLogErrorStatusDay::find()->Where('StatisticDate>:sd AND StatisticDate<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("StatisticDate desc")->All(\Yii::$app->db1);

            if (empty($fiveDateLists)) {
                return [];
            }
            $isToday = false;
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['StatisticDate']));
            $fitdates[$oneDate['error_status']][$toIntDay] = floatval($oneDate['Amount']);
        }
        #对处理了的数据再处理一遍，不存在的数据加上0
        $outCharts = [];
        $outCharts['categories'] = [];
        $outCharts['series'] = [];
        foreach ($fitdates as $key => $oneDate) {
            $start_date = date('Y-m-d', strtotime($fitDate));
            for ($i = 1; $i <= 5; $i++) {
                if (!in_array($start_date, $outCharts['categories'])) {
                    $outCharts['categories'][] = $start_date;
                }
                if (!isset($oneDate[$start_date])) {
                    $fitdates[$key][$start_date] = 0;
                }
                $start_date = date('Y-m-d', strtotime("-1 day", strtotime($start_date)));
            }
            ksort($fitdates[$key]);
        }
        foreach ($fitdates as $key => $oneDate) {
            $outCharts['series'][] = [
                'name' => '' . $key,
                'type' => 'line',
                'smooth' => true,
                'data' => array_values($oneDate),
            ];
            $outCharts['toptip'][] = '' . $key;
        }
        $outCharts['categories'] = array_reverse($outCharts['categories']);
        //标记是否为今天的数据，如果是今天的数据,那么对今天的数据进行实时统计处理
        if ($isToday) {
            $fitDate = date('Y-m-d 00:00:00');

            $after1Date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($fitDate)));
            //因为上面已经做了判断，所以今天的数据肯定会存在
            //获得今天最新的数据
            $errorstatusLists = \common\models\AccessLogErrorStatus::find()->select("count(*) nums,error_status")->where('request_time>:sd AND request_time<=:ed', [':sd' => $fitDate, ':ed' => $after1Date])->groupBy('error_status')->indexBy('error_status')->asArray()->all(\Yii::$app->db1);

            //对上面的数组进行处理
            foreach ($outCharts['series'] as $key => $oneVaue) {
                //把最近一个元素推出
                array_pop($outCharts['series'][$key]['data']);
                //推入一个新元素
                //如果没有这个数组就推入一个0的数据
                if (!isset($errorstatusLists[$oneVaue['name']]['nums'])) {
                    array_push($outCharts['series'][$key]['data'], 0);
                } else {
                    array_push($outCharts['series'][$key]['data'], floatval($errorstatusLists[$oneVaue['name']]['nums']));
                }
            }
        }
        return $outCharts;
    }

    /**
     * 获取中国地图对应信息
     */
    public static function fitChinaMap() {

        //获得ID，获得时间
        $proxy = \Yii::$app->request->get('proxy');
        $date = \Yii::$app->request->get('date');
        //如果这两个都为空那么就直接出错吧
        if (empty($proxy) || empty($date)) {
            return [];
        }

        $selectTable = NginxHightchartService::AccessStatistic17;
        $text = $date . '代理服务器' . $proxy . ':的全国访问量';
        $dataname = '访问量';
        switch ($proxy) {
            case 21:
                $selectTable = NginxHightchartService::AccessStatistic21;

                break;
            case 17:
                $selectTable = NginxHightchartService::AccessStatistic17;
                break;
            case 141:
            case 40:
            default :
                $selectTable = NginxHightchartService::AccessStatistic17;
        }
        $dateString = NginxService::findGroupString($date, "TopType=:topT", [':topT' => 'user_ip_1'], 'DetailType1', $selectTable);

        if (empty($dateString)) {
            return [];
        }
        //得到数据后，处理一个数组然后返回。
        $countryCity = \Yii::$app->params['pinyincity'];
        $redate = [];
        $maxshow = 0;
        foreach ($dateString as $oneDate) {
            //处理属于国内的数据
            if (in_array($oneDate['DetailType1'], $countryCity)) {
                $redate['series']['data'][] = ['name' => $oneDate['DetailType1'], 'value' => floatval($oneDate['totalNum'])];
                if ($oneDate['totalNum'] > $maxshow) {
                    $maxshow = $oneDate['totalNum'];
                }
            }
        }
        $redate['maxshow'] = $maxshow;
        $redate['text'] = $text;
        $redate['dataname'] = $dataname;
        return $redate;
    }

    /**
     * 获得世界访问量
     */
    public static function fitWorldMap() {

        //获得ID，获得时间
        $proxy = \Yii::$app->request->get('proxy');
        $date = \Yii::$app->request->get('date');
        //如果这两个都为空那么就直接出错吧
        if (empty($proxy) || empty($date)) {
            return [];
        }
        $selectTable = NginxHightchartService::AccessStatistic17;
        $text = $date . '代理服务器' . $proxy . ':的世界访问量';
        $dataname = '访问量';
        switch ($proxy) {
            case 21:
                $selectTable = NginxHightchartService::AccessStatistic21;

                break;
            case 17:
                $selectTable = NginxHightchartService::AccessStatistic17;
                break;
            case 141:
            case 40:
            default :
                $selectTable = NginxHightchartService::AccessStatistic17;
        }
        $dateString = NginxService::findGroupString($date, "TopType=:topT", [':topT' => 'user_ip_1'], 'DetailType1', $selectTable);
        if (empty($dateString)) {
            return [];
        }
        //得到数据后，处理一个数组然后返回。
        $countryCity = \Yii::$app->params['worldcountry'];
        $redate = [];
        $maxshow = 0;
        $chinaCity = \Yii::$app->params['pinyincity'];
        $chinanums = 0;
        foreach ($dateString as $oneDate) {
            //处理属于国内的数据
            if (in_array($oneDate['DetailType1'], $chinaCity)) {
                $chinanums += $oneDate['totalNum'];
            }
            //处理属于国内的数据
            if (in_array($oneDate['DetailType1'], $countryCity)) {
                $key = array_search($oneDate['DetailType1'], $countryCity); // $key = 2;
                $redate['series']['data'][] = ['name' => $key, 'value' => floatval($oneDate['totalNum'])];
            }
        }
        $maxshow = $chinanums;
        $redate['series']['data'][] = ['name' => 'China', 'value' => floatval($maxshow)];
        $redate['maxshow'] = $maxshow;
        $redate['text'] = $text;
        $redate['dataname'] = $dataname;
        return $redate;
    }

    /**
     * 获得平台与浏览器相关信息
     * @return string
     */
    public static function fitPlatBrower() {
        return self::CommonFunctionFitPie("TopType=:topT", [':topT' => 'plat_brower'], 'DetailType1', 'DetailType2');
    }

    /**
     * 这个方法跟上面那个很类似，又重复搬砖了。。
     */
    public static function fitErrors() {
        return self::CommonFunctionFitPie("TopType=:topT AND DetailType1<>200", [':topT' => 'status'], 'LogType', 'DetailType1');
    }

    /**
     * 处理手机平台与浏览器的关系
     */
    public static function fitMobilebrower() {
        return self::CommonFunctionFitPie("TopType=:topT AND DetailType2<>'other'", [':topT' => 'plat_mobile_brower'], 'DetailType2', 'DetailType3');
    }

    /**
     * 不想重复造代码，写个通用的方法
     */
    public static function CommonFunctionFitPie($queryWhere, $queryOptions, $groupColumn1, $groupColumn2) {


        //获得ID，获得时间
        $proxy = \Yii::$app->request->get('proxy');
        $date = \Yii::$app->request->get('date');
        //如果这两个都为空那么就直接出错吧
        if (empty($proxy) || empty($date)) {
            return [];
        }
        $selectTable = NginxHightchartService::AccessStatistic17;
        switch ($proxy) {
            case 21:
                $selectTable = NginxHightchartService::AccessStatistic21;

                break;
            case 17:
                $selectTable = NginxHightchartService::AccessStatistic17;
                break;
            case 141:
            case 40:
            default :
                $selectTable = NginxHightchartService::AccessStatistic17;
        }
        $dateString = NginxService::findGroupString($date, $queryWhere, $queryOptions, $groupColumn1 . ',' . $groupColumn2, $selectTable);

        if (empty($dateString)) {
            return [];
        }
        //开始处理数据，处理的方式是，一堆又一堆，不懂就看程序吧。
        //定义一个装大数组的array
        $plats = [];
        //定义一个二级数据
        $browers = [];
        //定义一个单独的浏览器显示
        $aloneBrower = [];
        //定义一个DATATEXT用来记录LABEL
        $datatext0 = [];
        $datatext1 = [];
        $datatext2 = [];
        foreach ($dateString as $oneDate) {
            //如果为空，那就为QYS空
            if (empty($oneDate[$groupColumn1])) {
                $oneDate[$groupColumn1] = 'qysone';
            }
            //如果为空，那就为QYS空
            if (empty($oneDate[$groupColumn2])) {
                $oneDate[$groupColumn2] = 'qystwo';
            }
            //判断第一个数组是否存在，存在就加数，不存在就初始化它为0
            if (isset($plats[$oneDate[$groupColumn1]])) {
                $plats[$oneDate[$groupColumn1]]+=$oneDate['totalNum'];
            } else {
                $datatext1[] = $oneDate[$groupColumn1];
                $plats[$oneDate[$groupColumn1]] = $oneDate['totalNum'];
            }
            if (isset($aloneBrower[$oneDate[$groupColumn2]])) {
                $aloneBrower[$oneDate[$groupColumn2]]+=$oneDate['totalNum'];
            } else {
                $datatext0[] = $oneDate[$groupColumn2];
                $aloneBrower[$oneDate[$groupColumn2]] = $oneDate['totalNum'];
            }
            //处理第二组的数据了
            $lines = $oneDate[$groupColumn1] . '-' . $oneDate[$groupColumn2];
            $datatext2[] = $lines;

            $browers[] = ['name' => $lines, 'value' => floatval($oneDate['totalNum'])];
        }
        //对$plats再做一次手术
        $plat = [];
        foreach ($plats as $key => $onePlat) {
            $plat[] = ['name' => $key, 'value' => floatval($onePlat)];
        }
        //对$aloneBrower再做一次手术
        $aBrower = [];
        foreach ($aloneBrower as $key1 => $oneB) {
            $aBrower[] = ['name' => $key1, 'value' => floatval($oneB)];
        }
        //对两个datatext1,datatext2合并
        $datetext = array_merge_recursive($datatext0, $datatext1, $datatext2);
        //配置生成数据
        $redate['datatext'] = $datetext;
        $redate['platdata'] = $plat;
        $redate['brower'] = $browers;
        $redate['alonebrower'] = $aBrower;
        return $redate;
    }

    /**
     * 处理访问量
     */
    public static function fitTotalVisit() {
        List($date, $selectTable) = self::CheckCommonSet();
        if (empty($date)) {
            return [];
        }
        $dateString = NginxService::findGroupString($date, "TopType=:status", [':status' => 'status'], 'CheckTime', $selectTable);

        if (empty($dateString)) {
            return [];
        }
        $legend = [$date];
        $xdata = [];
        $seriesdata = [];
        foreach ($dateString as $oneDate) {
            $fittime = explode(' ',$oneDate['CheckTime']);
            $xdata[] = $fittime[1];
            $seriesdata[] = floatval($oneDate['totalNum']);
        }
        return [
            'legend' => $legend,
            'xdata' => $xdata,
            'seriesdata' => $seriesdata
        ];
    }

    /**
     * 一个公用部分的方法处理
     */
    public static function CheckCommonSet() {
        //获得ID，获得时间
        $proxy = \Yii::$app->request->get('proxy');
        $date = \Yii::$app->request->get('date');
        //如果这两个都为空那么就直接出错吧
        if (empty($proxy) || empty($date)) {
            return ['', ''];
        }
        $selectTable = NginxHightchartService::AccessStatistic17;
        switch ($proxy) {
            case 21:
                $selectTable = NginxHightchartService::AccessStatistic21;

                break;
            case 17:
                $selectTable = NginxHightchartService::AccessStatistic17;
                break;
            case 141:
            case 40:
            default :
                $selectTable = NginxHightchartService::AccessStatistic17;
        }
        return [$date, $selectTable];
    }

}

?>