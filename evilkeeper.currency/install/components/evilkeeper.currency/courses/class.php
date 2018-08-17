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
        if ($this->arParams['ITEMS_COUNT'] == '') {
            $this->arResult['ITEMS_COUNT'] = 20;
        } else {
            $this->arResult['ITEMS_COUNT'] = intval($this->arParams['ITEMS_COUNT']);
        }

        $this->arResult['PAGINATION_TOP'] = $this->arParams['PAGINATION_TOP'];
        $this->arResult['PAGINATION_BOTTOM'] = $this->arParams['PAGINATION_BOTTOM'];

        $this->arResult['COLUMNS'] = $this->arParams['COLUMNS'];
        if (isset($this->arParams['INCLUDE_FILTER']) && $this->arParams['INCLUDE_FILTER'] == 'Y') {
            $this->arResult['FILTER'] = $this->arParams['FILTER'];
        }

        $this->includeComponentTemplate('list');
    }
}