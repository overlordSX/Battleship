<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\GameFieldEntity;
use Battleship\App\Database\Entity\PlayerEntity;

/**
 * Аттрибуты:
 * game_id,
 * player_id
 */
class GameFieldModel extends AbstractModel
{
    protected string $tableName = 'game_field';
    protected string $entityClassName = GameFieldEntity::class;


    /**
     * @param $gameId
     * @param $playerId
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getByGameAndPlayer($gameId, $playerId): AbstractEntity
    {
        return $this
            ->query()
            ->where('game_id', '=', $gameId)
            ->where('player_id', '=', $playerId)
            ->select()
            ->fetch();
    }
}