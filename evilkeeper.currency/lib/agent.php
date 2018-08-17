<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 01.08.18
 * Time: 10:34
 */

namespace Evilkeeper\Currency;

use \Bitrix\Main\Type\DateTime;

class Agent
{
    /**
     * @param string $strDate
     * @return string
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Exception
     */
    public static function getData($strDate = '')
    {
        $codes = explode(',', \Bitrix\Main\Config\Option::get('evilkeeper.currency', 'currencies'));
        $date = new DateTime($strDate);
        $date->add('1 day');

        if (empty($codes)) {
            return __METHOD__.'("'.$date->toString().'");';
        }

        $courses = Agent::getCourses($codes, $date);
        foreach ($courses as $code => $course) {
            CourseTable::add([
                'CODE' => $code,
                'DATE' => $date,
                'COURSE' => $course
            ]);
        }

        return __METHOD__.'("'.$date->toString().'");';
    }

    /**
     * @param array $codes
     * @param DateTime $date
     * @return array
     */
    private static function getCourses(array $codes, $date)
    {
        $client = new \SoapClient('http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL');

        $response = $client->__soapCall('GetCursOnDate', [['On_date' => $date->format('Y-m-d\TH:i:s')]]);
        $XMLResponse = \simplexml_load_string($response->GetCursOnDateResult->any);

        $res = [];
        foreach ($codes as $code) {
            $res[$code] = $XMLResponse->xpath("//ValuteCursOnDate[VchCode='$code']")[0]->Vcurs;
        }

        return $res;
    }
}