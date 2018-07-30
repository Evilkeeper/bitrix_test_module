<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 24.07.18
 * Time: 17:13
 */

use Bitrix\Main\GroupTable;

class UsersList extends CBitrixComponent
{
    /**
     * @return mixed|void
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function executeComponent()
    {
        global $APPLICATION;

        if ($this->arParams['SET_TITLE'] == 'Y') {
            $APPLICATION->SetTitle($this->arParams['NEW_TITLE']);
        }
        $this->arResult['ADD_DETAIL_URL'] = (isset($this->arParams['SEF_MODE']) && $this->arParams['SEF_MODE'] == 'Y');

        $this->arResult['groups'] = $this->getGroups($this->arResult['ADD_DETAIL_URL']);

        $this->includeComponentTemplate();
    }

    /**
     * @param bool $addURL
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    protected function getGroups($addURL)
    {
        $arGroups = GroupTable::getList();

        $groups = [];
        while ($group = $arGroups->fetch()) {
            if ($addURL) {
                $group['url'] = $this->createUrlForGroup($group);
            }
            $groups[] = $group;
        }

        return $groups;
    }

    protected function createUrlForGroup(array $group)
    {
        $replace = [
            '#ELEMENT_ID#' => $group['ID'],
            '#ELEMENT_CODE#' => $group['STRING_ID'],
        ];

        return str_replace(array_keys($replace), $replace, $this->arParams['SEF_URL_TEMPLATES']['detail']);
    }
}