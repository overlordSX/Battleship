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
        //echo "from resultToEntity";
        //var_dump($row);
        //echo "from resultToEntity";
        return new $classname($row);
    }
}