<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\GameEntity;
use Exception;

/**
 * Аттрибуты:
 * invite_code,
 * turn,
 * game_status_id,
 * player_id
 */
class GameModel extends AbstractModel
{
    protected string $tableName = 'game';
    protected string $entityClassName = GameEntity::class;

    /**
     * @param $firstPlayerId
     * @param $secondPlayerId
     * @return bool
     * @throws Exception
     */
    public function createNewGame($firstPlayerId, $secondPlayerId): bool
    {
        return $this
            ->insert([
                'turn' => $this->getRandomTurn(),
                'game_status_id' => 1,
                'first_player_id' => $firstPlayerId,
                'second_player_id' => $secondPlayerId
            ]);
    }

    /**
     * @param $playerId
     * @return GameEntity
     * @throws Exception
     */
    public function getGameByPlayerId($playerId): AbstractEntity
    {
        return $this
            ->query()
            ->select('id')
            ->where('first_player_id', '=', $playerId)
            ->fetch();
    }

    /**
     * @param $gameId
     * @return GameEntity
     * @throws Exception
     */
    public function getGameById($gameId): AbstractEntity
    {
        return $this
            ->query()
            ->select()
            ->where('id', '=', $gameId)
            ->fetch();
    }

    protected function getRandomTurn(): bool
    {
        return rand(-100, 100) > 0;
    }
}