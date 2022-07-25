<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\ShipPlacementEntity;

/**
 * Аттрибуты:
 * coordinate_x,
 * coordinate_y,
 * orientation,
 * ship_id,
 * game_field_id
 */
class ShipPlacementModel extends AbstractModel
{

    protected string $tableName = 'ship_placement';
    protected string $entityClassName = ShipPlacementEntity::class;
}