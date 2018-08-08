<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 31.07.18
 * Time: 12:45
 */

namespace Evilkeeper\Currency;

use \Bitrix\Main\Entity;
use \Bitrix\Main\Type;

class CourseTable extends Entity\DataManager
{
    /**
     * @inheritdoc
     */
    public static function getTableName()
    {
        return 'evilkeeper_currency_course';
    }

    /**
     * @inheritdoc
     * @throws \Bitrix\Main\SystemException
     */
    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true
            ]),
            new Entity\StringField('CODE', [
                'required' => true
            ]),
            new Entity\DatetimeField('DATE'),
            new Entity\FloatField('COURSE', [
                'required' => true
            ])
        ];
    }

    /**
     * @inheritdoc
     */
    public static function add(array $data)
    {
        if (!isset($data['DATE']) || $data == '') {
            $data['DATE'] = new Type\DateTime();
        }

        return parent::add($data);
    }
}