<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\GameEntity;
use Exception;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Аттрибуты:
 * invite_code,
 * turn,
 * game_status_id,
 * first_player_id,
 * second_player_id,
 * first_ready,
 * second_ready
 */
class GameModel extends AbstractModel
{
    protected string $tableName = 'game';
    protected string $entityClassName = GameEntity::class;

    /**
     * @throws Exception
     */
    #[ArrayShape(['id' => "int|null", 'code' => "string", 'invite' => "string", 'success' => "bool"])]
    public function start(): array
    {
        $playerModel = new PlayerModel();
        $firstPlayer = $playerModel->createPlayer();
        $secondPlayer = $playerModel->createPlayer();

        $gameModel = new GameModel();
        $currentGame = $gameModel->createNewGame($firstPlayer->getId(), $secondPlayer->getId());

        $gameFieldModel = new GameFieldModel();
        $gameFieldModel->create($currentGame->getId(), $firstPlayer->getId());
        $gameFieldModel->create($currentGame->getId(), $secondPlayer->getId());

        return [
            'id' => $currentGame->getId(),
            'code' => $firstPlayer->getCode(),
            'invite' => $secondPlayer->getCode(),
            'success' => true
        ];
    }

    /**
     * @param $gameId
     * @param $playerCode
     * @return array
     * @throws Exception
     */
    #[ArrayShape([
        'game' => "array",
        'fieldMy' => "array",
        'fieldEnemy' => "array",
        'usedPlaces' => "array",
        'success' => "bool"
    ])]
    public function getInfo($gameId, $playerCode): array
    {
        $gameModel = new GameModel();
        $currentGame = $gameModel->getGameById($gameId);

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);
        $enemyPlayer = $playerModel->getEnemyPlayer($currentGame, $currentPlayer);

        $isMyTurn = $playerModel->isMyTurn($currentGame, $currentPlayer);
        $isCurrentReady = $playerModel->isCurrentReady($currentGame, $currentPlayer);

        $gameFieldModel = new GameFieldModel();
        $myGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());
        $enemyGameField = $gameFieldModel->getByGameAndPlayer($gameId, $enemyPlayer->getId());

        //TODO здесь типо получать только расположение кораблей
        $myFieldAndUsedPlaces = new ShipPlacementModel();
        $myFieldAndUsedPlaces->getFieldAndUsedPlaces($myGameField->getId());

        $enemyFieldAndUsedPlaces = new ShipPlacementModel();
        $enemyFieldAndUsedPlaces->getFieldAndUsedPlaces($enemyGameField->getId(), true);

        //TODO как нибудь соеденить массивы расположения кораблей и расположения выстрелов,
        // чтобы получалось полное поле с кораблями и выстрелами


        /*var_dump($currentGame);*/
        return [
            'game' => [
                'id' => $currentGame->getId(),
                'status' => $currentGame->getGameStatusId(),
                'invite' => $enemyPlayer->getCode(),
                'myTurn' => $isMyTurn,
                'meReady' => $isCurrentReady
            ],
            'fieldMy' => $myFieldAndUsedPlaces->getField(),
            'fieldEnemy' => $enemyFieldAndUsedPlaces->getField(),
            'usedPlaces' => $myFieldAndUsedPlaces->getUsedPlaces(),
            'success' => true
        ];
    }

    /**
     * @throws Exception
     */
    public function changeTurn($gameId): bool
    {
        $currentGame = $this->query()->where('id', '=', $gameId)->fetch();
        return $this->update(
            [['id' => ['=' => $gameId]]],
            [['turn' => !$currentGame->getTurn()]]
        );
    }

    /**
     * @throws Exception
     */
    public function playersReady($gameId, $playerCode): array
    {
        $ready = [];

        $currentGame = $this->getGameById($gameId);

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $isFirstPlayerIsCurrent = $currentGame->getFirstPlayerId() === $currentPlayer->getId();
        if ($isFirstPlayerIsCurrent) {
            $ready['success'] = $this->update(
                [['id' => ['=' => $gameId]]],
                [['first_ready' => true]]
            );
            $ready['enemyReady'] = $currentGame->isSecondReady();
        } else {
            $ready['success'] = $this->update(
                [['id' => ['=' => $gameId]]],
                [['second_ready' => true]]
            );
            $ready['enemyReady'] = $currentGame->isFirstReady();
        }

        //TODO где подходящее место для этого?
        if ($ready['enemyReady']) {
            $this->update(
                [['id' => ['=' => $gameId]]],
                [['game_status_id' => 2]]
            );
        }

        return $ready;
    }

    /**
     * @param $firstPlayerId
     * @param $secondPlayerId
     * @return GameEntity
     * @throws Exception
     */
    public function createNewGame($firstPlayerId, $secondPlayerId): AbstractEntity
    {
        $this->insert([
            'turn' => $this->getRandomTurn(),
            'game_status_id' => 1,
            'first_player_id' => $firstPlayerId,
            'second_player_id' => $secondPlayerId
        ]);

        return $this->getGameByPlayerId($firstPlayerId);
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
            ->where('id', '=', $gameId)
            ->fetch();
    }

    protected function getRandomTurn(): bool
    {
        return rand(-100, 100) > 0;
    }
}