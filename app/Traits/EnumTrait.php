<?php

namespace App\Traits;


trait EnumTrait
{
    /**
     * accepts enums only
     *
     * @param $enumClass
     * @return []
     */
    public function getValuesByKeys($enumClass)
    {
        $resultArray = [];

        foreach ($enumClass::cases() as $elem) {
            $resultArray[$elem->name] = $elem->value;
        }

        return $resultArray;
    }

    /**
     * accepts enums only
     *
     * @param $enumClass
     * @return []
     */
    public function getKeysByValues($enumClass)
    {
        $resultArray = [];

        foreach ($enumClass::cases() as $elem) {
            $resultArray[$elem->value] = $elem->name;
        }

        return $resultArray;
    }
}
