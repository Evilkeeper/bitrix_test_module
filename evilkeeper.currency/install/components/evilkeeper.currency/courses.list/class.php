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

        $nav = new \Bitrix\Main\UI\AdminPageNavigation('nav-courses');
        $nav->setPageSize($this->arParams['ITEMS_COUNT'])->initFromUri();

        $res = CourseTable::getList([
            'select' => $this->arParams['COLUMNS'],
            'filter' => $this->arParams['FILTER'],
            'count_total' => true,
            'limit' => $nav->getLimit(),
            'offset' => $nav->getOffset(),
        ]);
        $nav->setRecordCount($res->getCount());

        $this->arResult = [
            'PAGINATION_TOP' => $this->arParams['PAGINATION_TOP'] == 'Y',
            'PAGINATION_BOTTOM' => $this->arParams['PAGINATION_BOTTOM'] == 'Y',
            'COLUMNS' => $this->arParams['COLUMNS'],
            'ITEMS' => $res->fetchAll(),
            'NAV' => $nav,
        ];

        $this->includeComponentTemplate();
    }
}