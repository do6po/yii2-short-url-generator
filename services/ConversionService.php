<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 9:52
 */

namespace app\services;


use app\models\Conversion;
use app\models\Url;
use Yii;
use yii\web\Request;

class ConversionService
{
    /**
     * @param Url $url
     * @param Request $request
     * @throws \yii\base\InvalidConfigException
     */
    public function create(Url $url, Request $request)
    {
        $conversion = Yii::createObject([
            'class' => Conversion::class,
            'ipAddress' => $request->userIP,
            'url_id' => $url->id,
        ]);

        $conversion->save();
    }
}