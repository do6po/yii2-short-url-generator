<?php
/**
 * Created by PhpStorm.
 * User: Boiko Sergii
 * Date: 18.07.2018
 * Time: 23:39
 */

namespace tests;



class UrlGenerateCest
{
    private $_url = '/url-generate';
    private $_form = '#url-generate-form';


    public function _before(FunctionalTester $I)
    {
    }

    public function seeEmptyFormFields(FunctionalTester $I)
    {
        $I->amOnRoute($this->_url);
        $I->seeInFormFields($this->_form, [
            'Url[long]' => '',
        ]);
    }
}