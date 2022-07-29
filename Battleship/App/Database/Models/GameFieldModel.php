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
     * @throws \Exception
     */
    public function create($gameId, $playerId): bool
    {
        return $this->insert([
            'game_id' => $gameId,
            'player_id' => $playerId
        ]);
    }

    /**
     * @param $gameId
     * @param $playerId
     * @return GameFieldEntity
     * @throws \Exception
     */
    public function getByGameAndPlayer($gameId, $playerId): AbstractEntity
    {
        return $this
            ->query()
            ->where('game_id', '=', $gameId)
            ->where('player_id', '=', $playerId)
            ->fetch();
    }
}