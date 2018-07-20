<?php

namespace app\models;

use app\helpers\ConfigHelper;
use app\helpers\GeneratorHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%urls}}".
 *
 * @property int $id
 * @property string $long
 * @property string $short
 * @property int $duration
 * @property int $expired_at
 * @property int $created_at
 *
 * @property Conversion[] $conversion
 */
class Url extends ActiveRecord
{
    const HOUR = 60 * 60;
    const DAY = self::HOUR * 24;
    const WEEK = self::DAY * 7;

    const DEFAULT_DURATION_VALUE = self::DAY;

    public static function tableName(): string
    {
        return '{{%urls}}';
    }

    public static function findByShort(string $short): self
    {
        return self::find()->byShort($short)->one();
    }

    public static function findAllExpired(): array
    {
        return self::find()->expired()->all();
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     */
    public function rules(): array
    {
        return [
            [['long'], 'required'],
            ['short', 'unique'],
            [['expired_at', 'created_at', 'duration'], 'integer'],
            ['expired_at', 'default', 'value' => time() + self::DEFAULT_DURATION_VALUE],
            [['long'], 'string', 'max' => 1995],
            [['long'], 'url'],
            ['short', 'default', 'value' => GeneratorHelper::generateRandomString(
                ConfigHelper::getShortUrlLength()
            )],
            [['short'], 'string', 'min' => 8, 'max' => 64],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'long' => Yii::t('app', 'URL'),
            'short' => Yii::t('app', 'Short URL'),
            'duration' => Yii::t('app', 'Duration'),
            'expired_at' => Yii::t('app', 'Expired At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    public static function find(): UrlsQuery
    {
        return new UrlsQuery(get_called_class());
    }

    public function getConversions(): ActiveQuery
    {
        return $this->hasMany(Conversion::class, ['url_id' => 'id']);
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    public function setDuration(int $duration): void
    {
        $this->expired_at = time() + $duration;
    }

    public function isExpired(): bool
    {
        return $this->expired_at <= time();
    }

    public function getDuration(): int
    {
        if ($this->expired_at > time()) {
            return $this->expired_at - time();
        }

        return 0;
    }

    public static function durations(): array
    {
        return [
            self::DAY => Yii::t('app', 'Day'),
            self::WEEK => Yii::t('app', 'Week'),
            self::DAY * 31 => Yii::t('app', 'Month'),
            self::DAY * 180 => Yii::t('app', '6 Months'),
            self::DAY * 360 => Yii::t('app', 'Year'),
        ];
    }

    public static function deleteAllExpired(): int
    {
        return self::deleteAll(['<', 'expired_at', time()]);
    }
}
