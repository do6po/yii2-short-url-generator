<?php

namespace tests\models;

use app\fixtures\ConversionFixtures;
use app\models\Conversion;
use app\models\Url;
use Codeception\Test\Unit;
use Yii;

class ConversionsTest extends Unit
{
    /**
     * @var \tests\UnitTester
     */
    protected $tester;

    /**
     * @var Conversion
     */
    private $_model;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function _before()
    {
        $this->_model = Yii::createObject([
            'class' => Conversion::class,
        ]);
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
     * @param $attribute
     * @param $value
     * @param $expected
     * @dataProvider validationDataProvider
     */
    public function testValidation($attribute, $value, $expected)
    {
        $this->_model->$attribute = $value;
        $this->assertEquals($expected, $this->_model->validate([$attribute]));
    }

    public function validationDataProvider(): array
    {
        return [
            'ip require' => ['ip', null, false],
            'ip string' => ['ip', 'asdasdasdas', false],
            'ip normal 1' => ['ip', ip2long('0.0.0.0'), true],
            'ip normal 2' => ['ip', ip2long('255.255.255.255'), true],
            'ip normal 3' => ['ip', ip2long('188.234.124.3'), true],
            'ip fake ip' => ['ip', ip2long('188.257.124.1'), false],
//            'ipAddress fake' => ['ipAddress', '188.257.124.1', false], #баг валидатора yii
            'ipAddress normal' => ['ipAddress', '188.253.124.1', true],
        ];
    }

    public function testSave()
    {
        $conversion = new Conversion([
            'ip' => ip2long('165.177.32.23'),
        ]);

        $this->assertFalse($conversion->save());

        $conversion->url_id = 1;
        $this->assertTrue($conversion->save());
    }

    /**
     * @param $urlId
     * @param $expectedCount
     * @dataProvider getConversionsDataProvider
     */
    public function testGetConversions($urlId, $expectedCount)
    {
        $url = Url::findOne($urlId);
        $this->assertEquals($expectedCount, $url->getConversions()->count());
    }

    public function getConversionsDataProvider()
    {
        return [
            [1, 3],
            [2, 6],
            [3, 1],
        ];
    }
}