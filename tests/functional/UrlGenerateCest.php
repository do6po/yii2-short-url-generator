<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 18.07.2018
 * Time: 23:39
 */

namespace tests;

use app\fixtures\UrlFixture;
use app\models\Url;
use Codeception\Util\HttpCode;

class UrlGenerateCest
{
    private $_url = '/url-generate';
    private $_form = '#url-generate-form';

    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute($this->_url);
    }

    public function _fixtures()
    {
        return [
            UrlFixture::class,
        ];
    }

    public function seeEmptyFormFields(FunctionalTester $I)
    {
        $I->seeInFormFields($this->_form, [
            'Url[long]' => '',
            'Url[duration]' => Url::DAY,
        ]);
    }

    public function submitFormSuccess(FunctionalTester $I)
    {
        $longUrl = 'https://www.google.com.ua/search?num=50&safe=active&source=hp&ei=qdlQW-2-NaePmgXXppuACg&q=bootstrap+3+input+size&oq=bootstrap+3+input+&gs_l=psy-ab.3.2.0l5j0i203k1l5.291.7906.0.10225.13.10.0.2.2.0.133.951.7j3.10.0....0...1c.1.64.psy-ab..1.12.974.0..35i39k1j0i131k1.0.OgKDJ8Y0VPI';

        $I->dontSeeRecord(Url::class, [
            'long' => $longUrl,
        ]);

        $I->submitForm($this->_form, [
            'Url[long]' => $longUrl,
            'Url[duration]' => Url::DAY,
        ]);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeInCurrentUrl(\yii\helpers\Url::to(['info', 'id' => 4]));

        $I->seeRecord(Url::class, [
            'long' => $longUrl,
        ]);
    }
}