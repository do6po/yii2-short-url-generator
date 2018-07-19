<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 19.07.18
 * Time: 14:23
 */

namespace app\fixtures;


use app\models\Url;
use yii\test\ActiveFixture;

class UrlFixture extends ActiveFixture
{
    public $modelClass = Url::class;
}