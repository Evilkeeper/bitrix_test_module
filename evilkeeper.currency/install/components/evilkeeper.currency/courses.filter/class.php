<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.07.18
 * Time: 15:59
 */

class CoursesFilter extends CBitrixComponent
{
    public function executeComponent()
    {
        if (in_array('CODE', $this->arParams)) {
            $this->arResult['CODE'] = htmlentities($_REQUEST['CODE'] ?: '');
        }
        if(in_array('DATE', $this->arParams)) {
            $this->arResult['DATE'] = [
                'FROM' => htmlentities($_REQUEST['DATE_FROM'] ?: ''),
                'TO' => htmlentities($_REQUEST['DATE_TO'] ?: '')
            ];
        }
        if(in_array('COURSE', $this->arParams)) {
            $this->arResult['COURSE'] = [
                'FROM' => htmlentities($_REQUEST['COURSE_FROM'] ?: ''),
                'TO' => htmlentities($_REQUEST['COURSE_TO'] ?: '')
            ];
        }

        if (empty($this->arResult)) {
            return [];
        }

        $this->includeComponentTemplate();
        return $this->makeFilter($this->arResult);
    }

    private function makeFilter(array $fields)
    {
        $res = [];
        foreach ($fields as $field => $value) {
            if (is_array($value)) {
                if ($value['FROM'] !== '') {
                    $res['>='.$field] = $value['FROM'];
                }
                if ($value['TO'] !== '') {
                    $res['<='.$field] = $value['TO'];
                }
            } else {
                if ($value !== '') {
                    $res['='.$field] = $value;
                }
            }
        }

        return $res;
    }
}