<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\GameEntity;
use Battleship\App\Database\Entity\PlayerEntity;

/**
 * Аттрибуты:
 * code
 */
class PlayerModel extends AbstractModel
{
    protected string $tableName = 'player';
    protected string $entityClassName = PlayerEntity::class;


    /**
     * @return PlayerEntity
     * @throws \Exception
     */
    public function createPlayer(): AbstractEntity
    {
        $code = $this->getNewPlayerCode();

        $this->insert(['code' => $code]);

        return $this->query()
            ->where('code', '=', $code)
            ->fetch();
    }

    /**
     * @param $playerCode
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getPlayerByCode($playerCode): AbstractEntity
    {

        return $this
            ->query()
            ->where('code', '=', $playerCode)
            ->fetch();
    }

    public function isFirstPlayerIsCurrent(int $firstPlayerId, int $currentPlayerId): bool
    {
        return $firstPlayerId === $currentPlayerId;

    }


    /**
     * @param GameEntity $currentGame
     * @param PlayerEntity $currentPlayer
     * @return PlayerEntity enemyPlayer
     * @throws \Exception
     */
    public function getEnemyPlayer(GameEntity $currentGame, PlayerEntity $currentPlayer): AbstractEntity
    {
        $enemyId = $this->isFirstPlayerIsCurrent($currentGame->getFirstPlayerId(), $currentPlayer->getId())
            ? $currentGame->getSecondPlayerId() : $currentGame->getFirstPlayerId();
        return $this->getPlayerById($enemyId);
    }


    /**
     * @param GameEntity $currentGame
     * @param PlayerEntity $currentPlayer
     * @return bool
     */
    public function isCurrentReady(GameEntity $currentGame, PlayerEntity $currentPlayer): bool
    {
        return $this->isFirstPlayerIsCurrent($currentGame->getFirstPlayerId(), $currentPlayer->getId())
            ? $currentGame->isFirstReady() : $currentGame->isSecondReady();
    }


    /**
     * @param GameEntity $currentGame
     * @param PlayerEntity $currentPlayer
     * @return bool
     */
    public function isMyTurn(GameEntity $currentGame, PlayerEntity $currentPlayer): bool
    {
        return $this->isFirstPlayerIsCurrent($currentGame->getFirstPlayerId(), $currentPlayer->getId())
            ? $currentGame->isFirstPlayerTurn() : $currentGame->isSecondPlayerTurn();
    }

    /**
     * @param $playerId
     * @return PlayerEntity
     * @throws \Exception
     */
    public function getPlayerById($playerId): AbstractEntity
    {

        return $this
            ->query()
            ->where('id', '=', $playerId)
            ->fetch();
    }

    protected function getNewPlayerCode(): string
    {
        return uniqid();
    }

    protected function getNewPlayerCodeMd5(): string
    {
        return md5(microtime(true));
    }
}