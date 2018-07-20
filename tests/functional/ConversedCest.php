<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 17:59
 */

namespace tests;


use app\fixtures\ConversionFixtures;
//use Codeception\Util\HttpCode;
//use yii\helpers\Url;

class ConversedCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _fixtures()
    {
        return [
            ConversionFixtures::class,
        ];
    }

    public function convert(FunctionalTester $I)
    {
//        $I->amOnRoute(Url::to(['ggdhsdjk']));
//
//        $I->seeResponseCodeIs(HttpCode::OK);
//        $I->seeInCurrentUrl(Url::to(['/url-generate/url-info', 'id' => 1])); #TODO выдает ошибку маршрута.
    }
}