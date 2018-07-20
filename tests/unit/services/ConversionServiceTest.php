<?php

namespace tests\services;

use app\fixtures\ConversionFixtures;
use app\models\Conversion;
use app\models\Url;
use app\services\ConversionService;
use Codeception\Stub;
use Codeception\Test\Unit;
use overals\GeoIP2\GeoIP2;
use Yii;
use yii\web\Request;

class ConversionServiceTest extends Unit
{
    /**
     * @var \tests\UnitTester
     */
    protected $tester;

    /**
     * @var ConversionService
     */
    private $_service;

    protected function _before()
    {
        $this->_service = new ConversionService();
    }

    protected function _after()
    {
    }

    public function _fixtures()
    {
        return [
            ConversionFixtures::class,
        ];
    }

    /**
     * @param $urlId
     * @param $ipAddress
     * @param $attributes
     * @throws \yii\base\InvalidConfigException
     * @throws \Exception
     * @dataProvider createDataProvider
     */
    public function testCreate($urlId, $ipAddress, $attributes)
    {
        $url = Url::findOne($urlId);

        $this->tester->dontSeeRecord(Conversion::class, $attributes);

        /** @var Request $request */
        $request = Stub::make(Request::class, [
            'getUserIp' => function () use ($ipAddress) {
                return $ipAddress;
            }
        ]);

        Yii::$app->set('request', $request);

        $geoip2 = new GeoIP2([
            'mmdb' => '@app/GeoLite2-City.mmdb',
        ]);
        Yii::$app->set('geoip2', $geoip2);

        $this->_service->create($url, $request);

        $this->tester->seeRecord(Conversion::class, $attributes);
    }

    public function createDataProvider()
    {
        return [
            [
                1,
                '188.94.124.1',
                [
                    'url_id' => 1,
                    'ip' => ip2long('188.94.124.1'),
                    'country' => 'Italy',
                    'city' => 'Vigonza',
                ],
            ],
            [
                1,
                '146.45.1.108',
                [
                    'url_id' => 1,
                    'ip' => ip2long('146.45.1.108'),
                    'country' => 'United States',
                    'city' => 'San Ramon',
                ],
            ],
        ];
    }
}