<?php

namespace app\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%conversions}}".
 *
 * @property int $id
 * @property int $url_id
 * @property int $ip
 * @property int $created_at
 *
 * @property string $country
 * @property string $city
 *
 * @property string $ipAddress
 *
 * @property Url $url
 */
class Conversion extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%conversions}}';
    }

    public function rules(): array
    {
        return [
            ['ipAddress', 'ip'],
            [['url_id', 'ip', 'created_at'], 'integer'],
            [['country', 'city'], 'string'],
            [['url_id'], 'exist', 'skipOnError' => true, 'targetClass' => Url::class, 'targetAttribute' => ['url_id' => 'id']],
            [['ipAddress', 'ip', 'url_id'], 'required'],
        ];
    }

    public function getUrl(): ActiveQuery
    {
        return $this->hasOne(Url::class, ['id' => 'url_id']);
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return long2ip($this->ip);
    }

    public function setIpAddress($ipAddress): void
    {
        $this->ip = ip2long($ipAddress);
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
}
