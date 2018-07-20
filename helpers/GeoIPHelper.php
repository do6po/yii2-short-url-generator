<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 16:55
 */

namespace app\helpers;

use overals\GeoIP2\Result;
use Yii;

class GeoIPHelper
{
    public static function getInfoByIP(string $ipAddress = null): Result
    {
        return Yii::$app->geoip2->getInfoByIP($ipAddress);
    }
}