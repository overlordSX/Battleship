<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Entity\GameEntity;
use Battleship\App\Database\Entity\GameFieldEntity;
use Battleship\App\Database\Entity\PlayerEntity;
use Battleship\App\Database\Entity\ShipPlacementEntity;
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

        $playerCode = $this->getNewGameCode();
        $inviteCode = $this->getNewGameCode();


        $playerModel = new PlayerModel();

        $playerModel->insert(['code' => $playerCode]);
        $playerModel->insert(['code' => $inviteCode]);


        $firstPlayer = $playerModel
            ->query()
            ->select()
            ->where('code', '=', $playerCode)
            ->fetch();

        /**
         * @var $secondPlayer PlayerEntity
         */
        $secondPlayer = $playerModel
            ->query()
            ->select()
            ->where('code', '=', $inviteCode)
            ->fetch();

        $gameModel = new GameModel();
        $gameModel->insert([
            'turn' => $this->getRandomTurn(),
            'game_status_id' => 1,
            'first_player_id' => $firstPlayer->getId(),
            'second_player_id' => $secondPlayer->getId()
        ]);

        /**
         * @var $currentGame GameEntity
         */
        $currentGame = $gameModel
            ->query()
            ->select('id')
            ->where('first_player_id', '=', $firstPlayer->getId())
            ->fetch();


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
        $currentGame = $gameModel
            ->query()
            ->select()
            ->where('id', '=', $gameId)
            ->fetch();

        $playerModel = new PlayerModel();

        /**
         * @var $currentPlayer PlayerEntity
         */
        $currentPlayer = $playerModel
            ->query()
            ->where('code', '=', $playerCode)
            ->select()
            ->fetch();

        $enemyId = $currentGame->getFirstPlayerId() === $currentPlayer->getId() ?
            $currentGame->getSecondPlayerId() : $currentGame->getFirstPlayerId();

        /**
         * @var $enemyPlayer PlayerEntity
         */
        $enemyPlayer = $playerModel
            ->query()
            ->where('id', '=', $enemyId)
            ->select()
            ->fetch();


        $gameFieldModel = new GameFieldModel();

        /**
         * @var $myGameField GameFieldEntity
         */
        $myGameField = $gameFieldModel
            ->query()
            ->where('game_id', '=', $currentGame->getId())
            ->where('player_id', '=', $currentPlayer->getId())
            ->select()
            ->fetch();


        /**
         * @var $enemyGameField GameFieldEntity
         */
        $enemyGameField = $gameFieldModel
            ->query()
            ->where('game_id', '=', $currentGame->getId())
            ->where('player_id', '=', $enemyPlayer->getId())
            ->select()
            ->fetch();


        $shipPlacementModel = new ShipPlacementModel();

        $fieldMy = $this->getEmptyPlacementArray();
        $fieldEnemy = $this->getEmptyPlacementArray();
        $usedPlaces = [];


        //TODO тут по сути нужно не первому и второму игроку по id, а именно currentPlayer & enemyPlayer

        /**
         * @var $myShipPlacedCount ShipPlacementEntity[]
         */
        $myShipPlacedCount = $shipPlacementModel
            ->query()
            ->where('game_field_id', '=', $myGameField->getId())
            ->selectCountRows()
            ->fetchCount();

        if ($myShipPlacedCount) {
            /**
             * @var $myPlacedShips ShipPlacementEntity[]
             */
            $myPlacedShips = $shipPlacementModel
                ->query()
                ->join('ship', 'ship_id', '=', 'id')
                ->where('game_field_id', '=', $myGameField->getId())
                ->select()
                ->fetchAll();

            foreach ($myPlacedShips as $placedShip) {
                $x = $placedShip->getCoordinateX();
                $y = $placedShip->getCoordinateY();
                $isHorizontal = $placedShip->getOrientation();
                $name = $placedShip->getCustom('name');

                $usedPlaces[] = $name;

                if ($isHorizontal) {
                    for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                        $fieldMy[$x + $i][$y] =
                            [
                                [$name, 0]
                                //TODO потом, когда то, будет проверка на видимость, в зависимости от попадания
                            ];
                    }

                } else {
                    for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                        $fieldMy[$x][$y + $i] =
                            [
                                [$name, 0]
                                //TODO потом, когда то, будет проверка на видимость, в зависимости от попадания
                            ];
                    }
                }

            }
        }


        $enemyShipPlacedCount = $shipPlacementModel
            ->query()
            ->where('game_field_id', '=', $enemyGameField->getId())
            ->selectCountRows()
            ->fetchCount();


        if ($enemyShipPlacedCount) {
            /**
             * @var $enemyPlacedShips ShipPlacementEntity[]
             */
            $enemyPlacedShips = $shipPlacementModel
                ->query()
                ->join('ship', 'ship_id', '=', 'id')
                ->where('game_field_id', '=', $enemyGameField->getId())
                ->select()
                ->fetchAll();

            foreach ($enemyPlacedShips as $placedShip) {
                $x = $placedShip->getCoordinateX();
                $y = $placedShip->getCoordinateY();
                $isHorizontal = $placedShip->getOrientation();
                $name = $placedShip->getCustom('name');


                if ($isHorizontal) {
                    for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                        $fieldEnemy[$x + $i][$y] =
                            [
                                [$name, 0]
                                //TODO потом, когда то, будет проверка на видимость, в зависимости от попадания
                            ];
                    }

                } else {
                    for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                        $fieldEnemy[$x][$y + $i] =
                            [
                                [$name, 0]
                                //TODO потом, когда то, будет проверка на видимость, в зависимости от попадания
                            ];
                    }
                }

            }
        }


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
            'fieldMy' => $fieldMy,
            'fieldEnemy' => $fieldEnemy,
            'usedPlaces' => $usedPlaces,
            'success' => true
        ];

        JsonUtil::makeAnswer($json);

    }

    public function setStatus()
    {
        echo "here i am setStatus<br>";
    }

    protected function getRandomTurn(): bool
    {
        return rand(-100, 100) > 0;
    }

    protected function getNewGameCode(): string
    {

        return uniqid();
    }

    protected function getNewGameCodeMd5(): string
    {
        return md5(microtime(true));
    }

    /**
     * массив 10х10 со значениями ["empty", 0]
     * @return array [[["empty", 0],...]], [[...],... ]],... [[....],..]]]
     */
    protected function getEmptyPlacementArray(): array
    {
        return array_fill(0, 10, array_fill(0, 10, ['empty', 0]));
    }

}