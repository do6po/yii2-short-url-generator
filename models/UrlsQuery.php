<?php

namespace app\models;

use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Url]].
 *
 * @see Url
 */
class UrlsQuery extends ActiveQuery
{
    public function byShort(string $short): self
    {
        return $this->andWhere(['short' => $short]);
    }

    /**
     * {@inheritdoc}
     * @return Url[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Url|array|null
     */
    public function one($db = null): Url
    {
        return parent::one($db);
    }

    public function expired(): self
    {
        return $this->where(['<', 'expired_at', time()]);
    }
}
