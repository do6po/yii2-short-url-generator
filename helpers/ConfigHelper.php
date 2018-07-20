<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 19.07.2018
 * Time: 22:43
 */

namespace app\helpers;

use Yii;

class ConfigHelper
{
    public static function getByKey($key)
    {
        return Yii::$app->params[$key];
    }

    public static function getShortUrlLength()
    {
        return self::getByKey('shortUrlLength');
    }
}