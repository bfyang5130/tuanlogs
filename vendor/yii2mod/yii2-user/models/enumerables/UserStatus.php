<?php
namespace yii2mod\user\models\enumerables;

use yii2mod\enum\helpers\BaseEnum;

/**
 * @author Igor Chepurnoy
 * @package yii2mod\cms\models\enumerables
 */
class UserStatus extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;

    public static $list = [
        self::ENABLED => 'Active',
        self::DISABLED => 'Inactive'
    ];
}