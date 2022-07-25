<?php

namespace Battleship\App\Database\Model;

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

}