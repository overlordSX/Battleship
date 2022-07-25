<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\ShipEntity;

/**
 * Аттрибуты:
 * size,
 * quantity
 */
class ShipModel extends AbstractModel
{

    protected string $tableName = 'ship';
    protected string $entityClassName = ShipEntity::class;


    /**
     * @param $name
     * @return ShipEntity
     * @throws \Exception
     */
    public function getByName($name): AbstractEntity
    {
        return $this
            ->query()
            ->where('name', '=', $name)
            ->select()
            ->fetch();
    }
}