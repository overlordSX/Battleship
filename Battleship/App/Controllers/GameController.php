<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Entity\GameEntity;
use Battleship\App\Database\Model\GameFieldModel;
use Battleship\App\Database\Model\GameModel;

use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShipPlacementModel;
use Exception;

class GameController implements ControllerInterface
{
    /**
     * сюда приходит POST, означающий старт игры
     *
     * нужно отдать обратно JSON:
     *      id игры,
     *      code игрока,
     *      invite код доступа для другого игрока
     *      success статус
     * @throws Exception
     */
    public function startNewGame(): void
    {

        $playerCode = $this->getNewPlayerCode();
        $inviteCode = $this->getNewPlayerCode();


        $playerModel = new PlayerModel();

        $playerModel->insert(['code' => $playerCode]);
        $playerModel->insert(['code' => $inviteCode]);


        $firstPlayer = $playerModel->getPlayerByCode($playerCode);
        $secondPlayer = $playerModel->getPlayerByCode($inviteCode);

        $gameModel = new GameModel();
        $gameModel->createNewGame($firstPlayer->getId(), $secondPlayer->getId());
        $currentGame = $gameModel->getGameByPlayerId($firstPlayer->getId());


        $gameFieldModel = new GameFieldModel();

        $gameFieldModel->insert([
            'game_id' => $currentGame->getId(),
            'player_id' => $firstPlayer->getId()
        ]);

        $gameFieldModel->insert([
            'game_id' => $currentGame->getId(),
            'player_id' => $secondPlayer->getId()
        ]);

        $data =
            [
                'id' => $currentGame->getId(),
                'code' => $firstPlayer->getCode(),
                'invite' => $secondPlayer->getCode(),
                'success' => true
            ];

        JsonUtil::makeAnswer($data);
    }

    /**
     * эти переменные приходят из адресной строки
     * @param $gameId
     * @param $playerCode
     * @return void
     * @throws Exception
     */
    public function getStatus($gameId, $playerCode): void
    {

        $gameModel = new GameModel();

        /**
         * @var $currentGame GameEntity
         */
        $currentGame = $gameModel->getGameById($gameId);


        $playerModel = new PlayerModel();


        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $enemyId = $currentGame->getFirstPlayerId() === $currentPlayer->getId() ?
            $currentGame->getSecondPlayerId() : $currentGame->getFirstPlayerId();


        $enemyPlayer = $playerModel->getPlayerById($enemyId);


        $gameFieldModel = new GameFieldModel();

        $myGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());
        $enemyGameField = $gameFieldModel->getByGameAndPlayer($gameId, $enemyPlayer->getId());


        $shipPlacementModel = new ShipPlacementModel();
        $myFieldAndUsedPlaces = $shipPlacementModel->getFieldAndUsedPlaces($myGameField->getId());

        $enemyFieldAndUsedPlaces = $shipPlacementModel->getFieldAndUsedPlaces($enemyGameField->getId());


        //TODO сделать класс Resource, там toArray приведение к массиву разными классами, вроде это про JSON
        // это про ship-placement
        // в двух вариантах


        $json = [
            'game' =>
                [
                    'id' => $currentGame->getId(),
                    'status' => $currentGame->getGameStatusId(),
                    //TODO можно сделать чтобы, в зависимости от кода доступа к игре
                    // отправлялась пригласительная ссылка с кодом другого игрока
                    'invite' => $enemyPlayer->getCode(),
                    'myTurn' => $currentGame->getTurn(),
                    'meReady' => false//TODO походу надо добавить в player поле ready
                ],
            'fieldMy' => $myFieldAndUsedPlaces['field'],
            'fieldEnemy' => $enemyFieldAndUsedPlaces['field'],
            'usedPlaces' => $myFieldAndUsedPlaces['usedPlaces'],
            'success' => true
        ];

        JsonUtil::makeAnswer($json);

    }

    public function setStatus()
    {
        echo "here i am setStatus<br>";
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