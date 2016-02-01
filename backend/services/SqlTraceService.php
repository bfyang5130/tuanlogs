<?php

namespace backend\services;

use common\models\ApplicateName;
use common\models\ErrorLog;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Description of SqlTraceService
 *
 * @author Administrator
 */
class SqlTraceService {

    public static function getSqlTraceDbType(){
        $query = new Query() ;
        $query->select("databasetype")
            ->from("SqlTrace")
            ->distinct() ;
        $dbtypes = $query->all() ;
        $dbtype_item = ArrayHelper::map($dbtypes,"databasetype","databasetype") ;
        return $dbtype_item ;
    }

}

?>