<?php

namespace tests\models;

use app\models\Url;
use Codeception\Test\Unit;
use app\fixtures\UrlFixture;
use app\helpers\GeneratorHelper;
use Yii;


class UrlTest extends Unit
{
    /**
     * @var \tests\UnitTester
     */
    protected $tester;

    /**
     * @var Url
     */
    private $_model;

    /**
     * @throws \yii\base\InvalidConfigException
     */
    protected function _before()
    {
        $this->_model = Yii::createObject([
            'class' => Url::class,
        ]);
    }

    protected function _after()
    {
    }

    public function _fixtures()
    {
        return [
            UrlFixture::class,
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
            'long required' => ['long', null, false],
            'long too long url' => ['long', sprintf('http://short-url-generator.test/g?=%s', str_repeat('a', 1965)), false],
            'long normal url' => ['long', sprintf('http://short-url-generator.test/g?=%s', str_repeat('a', 1960)), true],
            'long not url' => ['long', 'aaaaa', false],
            'short unique' => ['short', 'apsodksc', false],
            'short required' => ['short', null, true],
            'short too short' => ['short', 'fgksdl', false],
            'short too long' => ['short', str_repeat('a', 65), false],
            'short normal' => ['short', '12345678', true],
            'expired_at string' => ['expired_at', 'asdasd', false],
            'expired_at integer' => ['expired_at', time(), true],
        ];
    }

    /**
     * @throws \yii\base\Exception
     */
    public function testCreate()
    {
        $url = new Url([
            'long' => 'https://www.google.com.ua/search?num=50&safe=active&ei=sIFQW7eHDYuB0wKKxYTQDQ&q=yii+random+string&oq=yii+random+&gs_l=psy-ab.3.0.0j0i22i30k1l9.96095.98167.0.99535.7.7.0.0.0.0.156.736.4j3.7.0....0...1c.1.64.psy-ab..0.7.733...35i39k1j0i67k1j0i203k1j0i20i263k1j0i22i10i30k1.0.mjfTGMm859Q',
            'short' => GeneratorHelper::generateRandomString(8),
        ]);

        $this->assertTrue($url->save());
    }

    public function testFindByShort()
    {
        $short = 'ggdhsdjk';
        $url = Url::findByShort($short);

        $this->assertEquals(
            'https://www.yiiframework.com/doc/api/2.0/yii-base-security#generateRandomString()-detail',
            $url->long
        );

        $short = 'apsodksc';
        $url = Url::findByShort($short);

        $this->assertEquals(
            'http://winitpro.ru/index.php/2017/06/08/sozdanie-zadaniya-planirovshhika-s-pomoshhyu-powershell/',
            $url->long
        );
    }

    public function testFindAllExpired()
    {
        $expiredUrls = Url::findAllExpired();

        $this->assertTrue(is_array($expiredUrls));
        $this->assertEquals(2, count($expiredUrls));

    }

    public function testGetDuration()
    {
        $this->_model->expired_at = time() + 60;
        $this->assertTrue($this->_model->duration <= 60 || $this->_model->duration > 59);

        $this->_model->expired_at = time();
        $this->assertEquals(0, $this->_model->duration);
    }

    public function testSetDuration()
    {
        $this->_model->duration = 60;
        $expected = $this->_model->expired_at;
        $this->assertTrue($expected > time() + 59 || $expected < time() + 61);
    }
}