<?php
/**
 * Created by PhpStorm.
 * User: box
 * Date: 20.07.18
 * Time: 15:59
 */

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class ConversionSearch extends Conversion
{
    public function attributeLabels()
    {
        return [
            'created_at' => Yii::t('app', 'Conversed at'),
            'ipAddress' => Yii::t('app', 'Ip address'),
        ];
    }

    public function search(Url $url)
    {
        $query = Conversion::find()->where(['url_id' => $url->id]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'created_at',
                    'ipAddress' => [
                        'asc' => ['ip' => SORT_ASC],
                        'desc' => ['ip' => SORT_DESC],
                    ],
                ],
                'defaultOrder' => ['created_at' => SORT_DESC]
            ],
            'pagination' => ['pageSize' => 50],
        ]);
    }
}