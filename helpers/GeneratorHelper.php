<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 19.07.18
 * Time: 15:17
 */

namespace app\helpers;

use Yii;

class GeneratorHelper
{
    /**
     * @param int $length
     * @return string
     * @throws \yii\base\Exception
     */
    public static function generateRandomString(int $length)
    {
        return Yii::$app->security->generateRandomString($length);
    }
}