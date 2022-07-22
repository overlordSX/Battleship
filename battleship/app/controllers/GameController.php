<?php

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
     */
    public function startNewGame(): void
    {

        $playerCode = $this->getNewGameCode();
        $inviteCode = $this->getNewGameCode();


        $playerModel = new PlayerModel();
        $playerModel
            ->insert(['code' => $playerCode]);
        $playerModel
            ->clear()
            ->insert(['code' => $inviteCode]);


        $firstPlayer = $playerModel
            ->clear()
            ->query()
            ->select('*')
            ->where('code', '=', ':code')
            ->fetch(['code' => $playerCode]);

        /**
         * @var $secondPlayer PlayerEntity
         */
        $secondPlayer = $playerModel
            ->clear()
            ->query()
            ->select('*')
            ->where('code', '=', ':code')
            ->fetch(['code' => $inviteCode]);

        $gameModel = new GameModel();
        $gameModel
            ->insert(
                [
                    'turn' => $this->getRandomTurn(),
                    'game_status_id' => 1,
                    'first_player_id' => $firstPlayer->getId(),
                    'second_player_id' => $secondPlayer->getId()
                ]
            );

        /**
         * @var $currentGame GameEntity
         */
        $currentGame = $gameModel
            ->clear()
            ->query()
            ->select('id')
            ->where('first_player_id', '=', ':id')
            ->fetch(['id' => $firstPlayer->getId()]);


        //TODO так же тут должны создаваться game_field
        // для этой игры, для каждого игрока

        $gameFieldModel = new GameFieldModel();

        $gameFieldModel
            ->insert(['game_id' => $currentGame->getId(), 'player_id' => $firstPlayer->getId()]);
        $gameFieldModel
            ->clear()
            ->insert(['game_id' => $currentGame->getId(), 'player_id' => $secondPlayer->getId()]);

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
     */
    public function getStatus($gameId, $playerCode)
    {

        $gameModel = new GameModel();

        /**
         * @var $currentGame GameEntity
         */
        $currentGame = $gameModel
            ->query()
            ->select('*')
            ->where('id', '=', ':gameId')
            ->fetch(['gameId' => $gameId]);

        $playerModel = new PlayerModel();

        /**
         * @var $currentPlayer PlayerEntity
         */
        $currentPlayer = $playerModel
            ->query()
            ->where('code', '=', ':code')
            ->select('*')
            ->fetch(['code' => $playerCode]);

        $enemyId = $currentGame->getFirstPlayerId() === $currentPlayer->getId() ?
            $currentGame->getSecondPlayerId() : $currentGame->getFirstPlayerId();

        /**
         * @var $enemyPlayer PlayerEntity
         */
        $enemyPlayer = $playerModel
            ->clear()
            ->query()
            ->where('id', '=', ':id')
            ->select('*')
            ->fetch(['id' => $enemyId]);


        $gameFieldModel = new GameFieldModel();

        /**
         * @var $myGameField GameFieldEntity
         */
        $myGameField = $gameFieldModel
            ->query()
            ->where('game_id', '=', ':gameId')
            ->where('player_id', '=', ':playerId')
            ->select('*')
            ->fetch(
                [
                    'gameId' => $currentGame->getId(),
                    'playerId' => $currentPlayer->getId()
                ]
            );


        /**
         * @var $enemyGameField GameFieldEntity
         */
        $enemyGameField = $gameFieldModel
            ->clear()
            ->query()
            ->where('game_id', '=', ':gameId')
            ->where('player_id', '=', ':playerId')
            ->select('*')
            ->fetch(
                [
                    'gameId' => $currentGame->getId(),
                    'playerId' => $enemyPlayer->getId()
                ]
            );


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
            ->where('game_field_id', '=', ':id')
            ->selectRow('select count(*) as count')
            ->fetchToArray(['id' => $myGameField->getId()])['count'];

        if ($myShipPlacedCount) {
            /**
             * @var $myPlacedShips ShipPlacementEntity[]
             */
            $myPlacedShips = $shipPlacementModel
                ->clear()
                ->query()
                ->join('ship', 'ship_id', '=', 'id')
                ->where('game_field_id', '=', ':id')
                ->selectRow('select *')
                ->fetchAll(['id' => $myGameField->getId()]);

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
            ->clear()
            ->query()
            ->where('game_field_id', '=', ':id')
            ->selectRow('select count(*) as count')
            ->fetchToArray(['id' => $enemyGameField->getId()])['count'];


        if ($enemyShipPlacedCount) {
            /**
             * @var $enemyPlacedShips ShipPlacementEntity[]
             */
            $enemyPlacedShips = $shipPlacementModel
                ->clear()
                ->query()
                ->join('ship', 'ship_id', '=', 'id')
                ->where('game_field_id', '=', ':id')
                ->selectRow('select *')
                ->fetchAll(['id' => $enemyGameField->getId()]);

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

    /**
     * @return string [[playerCode], [inviteCode]]
     */
    protected function getNewGameCode(): string
    {
        /*var_dump(md5(microtime(true)));
        var_dump(md5(microtime(true)));*/
        return uniqid();
    }

    /**
     * массив 10х10 со значениями ["empty", 0]
     * @return array [[["empty", 0],...]], [[...],... ]],... [[....],..]]]
     */
    protected function getEmptyPlacementArray(): array
    {
        return array_fill(
            0,
            10,
            array_fill(0, 10, ['empty', 0])
        );
    }

}