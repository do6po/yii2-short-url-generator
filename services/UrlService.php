<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 19.07.2018
 * Time: 22:30
 */

namespace app\services;

use app\models\Url;

class UrlService
{
    /**
     * @param Url $url
     * @return string
     */
    public function create(Url $url): string
    {
        if ($url->save()) {
            return $url->id;
        }

        return false;
    }
}