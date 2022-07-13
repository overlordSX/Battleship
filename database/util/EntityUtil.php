<?php

class EntityUtil
{
    /**
     * @param $classname
     * @param $array
     * @return AbstractEntity[]
     */
    public static function resultToListOfEntities($classname, $array): array
    {
        $listOfEntities = [];
        foreach ($array as $row) {
            $listOfEntities[] = new $classname($row);
        }

        return $listOfEntities;
    }

    public static function resultToEntity($classname, $row): AbstractEntity
    {
        return new $classname($row);
    }
}