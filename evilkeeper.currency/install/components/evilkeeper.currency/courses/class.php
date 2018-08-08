<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.07.18
 * Time: 18:26
 */

class Courses extends CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult['COLUMNS'] = $this->arParams['COLUMNS'];
        if ($this->arParams['INCLUDE_FILTER']) {
            $this->arResult['FILTER'] = $this->arParams['FILTER'];
        }

        $this->includeComponentTemplate('list');
    }
}