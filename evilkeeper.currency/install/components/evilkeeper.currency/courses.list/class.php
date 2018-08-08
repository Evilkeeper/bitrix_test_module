<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.07.18
 * Time: 14:10
 */

namespace Evilkeeper\Currency;

use \Bitrix\Main\Localization\Loc;

class CoursesList extends \CBitrixComponent
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Bitrix\Main\LoaderException
     */
    public function executeComponent()
    {
        if (!\Bitrix\Main\Loader::includeModule('evilkeeper.currency')) {
            throw new \RuntimeException(Loc::getMessage('MODULE_ERROR'));
        }

        $this->arResult = [
            'COLUMNS' => $this->arParams['COLUMNS'],
            'ITEMS' => CourseTable::getList([
                'select' => $this->arParams['COLUMNS'],
                'filter' => $this->arParams['FILTER']
            ])->fetchAll()
        ];

        $this->includeComponentTemplate();
    }
}