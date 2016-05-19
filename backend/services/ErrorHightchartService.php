<?php

namespace backend\services;

use backend\services\AppcationNameService;
use yii\helpers\Url;

/**
 * Description of AccessLogService
 *
 * @author Administrator
 */
class ErrorHightchartService {

    /**
     * 获得五个错误的柱状图
     * @param type $condition
     * @param type $conPrams
     * @param type $limit
     * @param type $order
     * @return type
     */
    public static function find5Column($condition, $conPrams, $limit, $order) {
        $dateString = AppcationNameService::findAppliName($condition, $conPrams, $limit, $order);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        $starttime = date('Y-m-01 00:00:00');
        $endtime = date('Y-m-01 00:00:00', strtotime('+1 month', strtotime($starttime)));
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['appname'];
            $otherCountry['series']['data'][] = ['url' => Url::toRoute('/errors/index') . '?ErrorLogSearch%5BApplicationId%5D=' . $oneDate['appname'] . '&ErrorLogSearch%5Bstart_date%5D=' . $starttime . '&ErrorLogSearch%5Bend_date%5D=' . $endtime, 'name' => $oneDate['appname'], 'y' => floatval($oneDate['logtotal'])];
            $otherCountry['series']['name'] = '数量';
            $otherCountry['series']['color'] = 'red';
        }
        return ['in_country' => $otherCountry];
    }

    /**
     * 向echarts提供的五个柱子
     */
    public static function find5ColumnEcharts($condition, $conPrams, $limit, $order) {
        $dateString = AppcationNameService::findAppliName($condition, $conPrams, $limit, $order);
        if (empty($dateString)) {
            return [];
        }
        $otherCountry = [];
        foreach ($dateString as $oneDate) {
            $otherCountry['categories'][] = $oneDate['appname'];
            //$otherCountry['series']['data'][] = ['url' => Url::toRoute('/errors/index') . '?ErrorLogSearch%5BApplicationId%5D=' . $oneDate['appname']. '&ErrorLogSearch%5Bstart_date%5D=' . $starttime. '&ErrorLogSearch%5Bend_date%5D=' . $endtime, 'name' => $oneDate['appname'], 'y' => floatval($oneDate['logtotal'])];
            $otherCountry['series']['data'][] = floatval($oneDate['logtotal']);
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

    public static function findAllLine() {
        #获得所有错误类型
        $allErrorsType = \common\models\ApplicateName::find()->asArray()->select('appname')->where('logtype=0')->all();
        foreach ($allErrorsType as $oneDate) {
            $fitAllErrorsType[] = $oneDate['appname'];
        }
        $whereString = implode(',', $fitAllErrorsType);
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = \common\models\ErrorLogDay::find()->where(["ApplicationId" => $fitAllErrorsType])->andWhere('Date>:sd AND Date<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("Date desc")->All();
        //如果为空，就取最后一个时间，以最后一个时间点为准
        //标记是否为最新的数据
        $isToday = true;
        if (empty($fiveDateLists)) {
            $lastDay = \common\models\ErrorLogDay::find()->orderBy("Id desc")->one();
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->Date));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = \common\models\ErrorLogDay::find()->where(["ApplicationId" => $fitAllErrorsType])->andWhere('Date>:sd AND Date<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("Date desc")->All();

            if (empty($fiveDateLists)) {
                return [];
            }
            $isToday = false;
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['Date']));
            $fitdates[$oneDate['ApplicationId']][$toIntDay] = floatval($oneDate['Number']);
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
            $errorstatusLists = \common\models\ErrorLog::find()->select("count(*) nums,ApplicationId")->where('AddDate>:sd AND AddDate<=:ed', [':sd' => $fitDate, ':ed' => $after1Date])->groupBy('ApplicationId')->indexBy('ApplicationId')->asArray()->all();

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

    public static function findAllLineEcharts() {
        #获得所有错误类型
        $allErrorsType = \common\models\ApplicateName::find()->asArray()->select('appname')->where('logtype=0')->all();
        foreach ($allErrorsType as $oneDate) {
            $fitAllErrorsType[] = $oneDate['appname'];
        }
        $whereString = implode(',', $fitAllErrorsType);
        #获得最近五天的数据
        $fitDate = date('Y-m-d 00:00:00');

        $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
        $fiveDateLists = \common\models\ErrorLogDay::find()->where(["ApplicationId" => $fitAllErrorsType])->andWhere('Date>:sd AND Date<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("Date desc")->All();
        //如果为空，就取最后一个时间，以最后一个时间点为准
        //标记是否为最新的数据
        $isToday = true;
        if (empty($fiveDateLists)) {
            $lastDay = \common\models\ErrorLogDay::find()->orderBy("Id desc")->one();
            if (empty($lastDay)) {
                return [];
            }
            $fitDate = date('Y-m-d 00:00:00', strtotime($lastDay->Date));
            $after5Date = date('Y-m-d 00:00:00', strtotime('-5 day', strtotime($fitDate)));
            $fiveDateLists = \common\models\ErrorLogDay::find()->where(["ApplicationId" => $fitAllErrorsType])->andWhere('Date>:sd AND Date<=:ed', [':sd' => $after5Date, ':ed' => $fitDate])->asArray()->orderBy("Date desc")->All();

            if (empty($fiveDateLists)) {
                return [];
            }
            $isToday = false;
        }
        #//开始处理这五天的数据
        $fitdates = [];
        foreach ($fiveDateLists as $key => $oneDate) {
            //处理成2016-5-6形式的日期
            $toIntDay = date("Y-m-d", strtotime($oneDate['Date']));
            $fitdates[$oneDate['ApplicationId']][$toIntDay] = floatval($oneDate['Number']);
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
        //处理一个上面显示的数据
        foreach ($fitdates as $key => $oneDate) {
            $outCharts['series'][] = [
                'name' => $key,
                'type' => 'line',
                'smooth' => true,
                'data' => array_values($oneDate),
            ];
            $outCharts['toptip'][]=$key;
        }
        $outCharts['categories'] = array_reverse($outCharts['categories']);
        //标记是否为今天的数据，如果是今天的数据,那么对今天的数据进行实时统计处理
        if ($isToday) {
            $fitDate = date('Y-m-d 00:00:00');

            $after1Date = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($fitDate)));
            //因为上面已经做了判断，所以今天的数据肯定会存在
            //获得今天最新的数据
            $errorstatusLists = \common\models\ErrorLog::find()->select("count(*) nums,ApplicationId")->where('AddDate>:sd AND AddDate<=:ed', [':sd' => $fitDate, ':ed' => $after1Date])->groupBy('ApplicationId')->indexBy('ApplicationId')->asArray()->all();

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

}

?>