<?php

namespace Battleship\App\Database\Util;


use Battleship\App\Database\Entity\AbstractEntity;

class EntityUtil
{
    /**
     * @param $classname
     * @param $array
     * @return AbstractEntity[]
     */
    public static function resultToListOfEntities($classname, $array): array
    {
        if (!$array) {
            return [];
        }

        $listOfEntities = [];
        foreach ($array as $row) {
            $listOfEntities[] = new $classname($row);
        }

        return $listOfEntities;
    }

    /**
     * @param $classname
     * @param $row
     * @return AbstractEntity
     */
    public static function resultToEntity($classname, $row): AbstractEntity
    {
        return new $classname($row);
    }
}