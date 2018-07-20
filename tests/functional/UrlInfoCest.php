<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 17:41
 */

namespace tests;


use app\fixtures\ConversionFixtures;
use Codeception\Util\HttpCode;

class UrlInfoCest
{
    private $_url = '/url-generate/url-info';

    public function _before(FunctionalTester $I)
    {
    }

    public function _fixtures()
    {
        return [
            ConversionFixtures::class,
        ];
    }

    public function seeExpiredUrlInfo(FunctionalTester $I)
    {
        $urlId = 2;

        $I->amOnRoute($this->_url, [
            'id' => $urlId,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeInField('short-url', 'http://localhost/index-test.php?r=ggdhsdjk');
        $I->see('This URL is expired', 'div.alert-warning');

        $I->see('18.194.232.1', 'td');
        $I->see('Germany', 'td');
        $I->see('Frankfurt am Main', 'td');
    }

    public function seeActiveUrlInfo(FunctionalTester $I)
    {
        $urlId = 1;

        $I->amOnRoute($this->_url, [
            'id' => $urlId,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeInField('short-url', 'http://localhost/index-test.php?r=apsodksc');
        $I->dontSee('This URL is expired', 'div.alert-warning');

        $I->see('8.94.23.122', 'td');
        $I->see('United States', 'td');
        $I->see('Monroe', 'td');
    }
}