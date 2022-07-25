<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\GameStatusEntity;

/**
 * Аттрибуты:
 * status,
 * description
 */
class GameStatusModel extends AbstractModel
{

    protected string $tableName = 'game_status';
    protected string $entityClassName = GameStatusEntity::class;

}