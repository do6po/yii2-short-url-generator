<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 10:44
 */

namespace app\fixtures;


use app\models\Conversion;
use yii\test\ActiveFixture;

class ConversionFixtures extends ActiveFixture
{
    public $modelClass = Conversion::class;
    public $depends = [
        UrlFixture::class,
    ];
}