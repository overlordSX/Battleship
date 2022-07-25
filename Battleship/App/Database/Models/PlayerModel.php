<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\PlayerEntity;

/**
 * Аттрибуты:
 * code
 */
class PlayerModel extends AbstractModel
{
    protected string $tableName = 'player';
    protected string $entityClassName = PlayerEntity::class;
}