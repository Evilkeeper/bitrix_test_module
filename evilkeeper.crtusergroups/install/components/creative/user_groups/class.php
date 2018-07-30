<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 24.07.18
 * Time: 18:56
 */

class MoreThanUsersList extends CBitrixComponent
{
    public function executeComponent()
    {
        $arVariables = [];
        $arVariableAliases = [];
        $arComponentVariables = array(
            "ELEMENT_ID"
        );
        $arDefaultUrlTemplates = [
            'list' => 'index.php',
            'detail' => '#ELEMENT_ID#/'
        ];

        $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates, $this->arParams['SEF_URL_TEMPLATES']);
        $componentPage = CComponentEngine::ParseComponentPath($this->arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables);
        CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

        if ($componentPage == 'detail') {
            $this->arParams['ELEMENT_ID'] = $arVariables['ELEMENT_ID'];
        } else {
            $this->arParams['SEF_URL_TEMPLATES'] = $arUrlTemplates;
        }

        $this->includeComponentTemplate($componentPage);
    }
}