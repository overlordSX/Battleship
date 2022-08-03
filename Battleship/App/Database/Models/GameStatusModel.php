<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
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

    /**
     * @param int $gameStatusNumber
     * @return GameStatusEntity
     * @throws \Exception
     */
    public function getStatus(int $gameStatusNumber): AbstractEntity
    {
        return $this->query()
            ->where('status', '=', $gameStatusNumber)
            ->select('description')
            ->fetch();
    }
}