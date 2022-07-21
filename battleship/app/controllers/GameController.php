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

        //$newPlayer->insert(['code' => $playerCode]);

        $playerModel = new PlayerModel();
        $playerModel
            ->insert(['code' => $playerCode]);
        $playerModel
            ->clear()
            ->insert(['code' => $inviteCode]);


        $firstPlayer = $playerModel
            //$dbPlayerId = $playerModel
            ->clear()
            ->query()
            ->select('*')
            ->where2('code', '=', ':code')
            //->getQuery());
            ->fetch(['code' => $playerCode]);

        /**
         * @var $secondPlayer PlayerEntity
         */
        $secondPlayer = $playerModel
            //TODO сейчас я могу получить только весь объект целиком,
            // если буду какой то конкретный атрибут получать, то будет ошибка
            //$dbInvitePlayerId = $playerModel
            ->clear()
            ->query()
            ->select('*')
            ->where2('code', '=', ':code')
            //->getQuery());
            ->fetch(['code' => $inviteCode]);

        //var_dump($dbPlayerId);

        $gameModel = new GameModel();
        $gameModel
            ->insert(
                [
                    'invite_code' => $inviteCode,
                    'turn' => $this->getRandomTurn(),
                    'game_status_id' => 1,
                    'player_id' => $firstPlayer->getId()
                ]
            );

        /**
         * @var $currentGame GameEntity
         */
        $currentGame = $gameModel
            //$dbGameId = $gameModel
            ->clear()
            ->query()
            ->select('*')
            ->where2('player_id', '=', ':id')
            ->fetch(['id' => $firstPlayer->getId()]);

        //echo ' ' . $dbGameId;

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

        //var_dump(count($data));
        JsonUtil::makeAnswer($data);


        //TODO было нормальным
        /*foreach ($gameCodes as $playerCode) {
            //$newGameQuery->insert(['code' => $playerCode]);

            $newGameQuery->
            insertInto('player', [[':playerCode']], ['playerCode' => $playerCode]);
        }*/

    }

    /**
     * эти переменные приходят из адресной строки
     * @param $gameId
     * @param $playerCode
     * @return void
     */
    public function getStatus($gameId, $playerCode)
    {
        //echo 'игра: ' . $gameId . ' code: ' . $playerCode . '<br>';

        //header('Content-Type: application/json');

        $gameModel = new GameModel();

        //TODO по сути же тут нужно получить объект, GameEntity,
        // следовательно нужно реализовать получение данных из строки
        /**
         * @var $currentGame GameEntity
         */
        $currentGame = $gameModel
            ->query()
            ->select('*')
            ->where2('id', '=', ':gameId')
            ->fetch(['gameId' => $gameId]);


        /*
        print_r($currentGame);

        $firstPlayerId = $currentGame['player_id'];

        $inviteCode = $currentGame['invite_code'];*/

        /*var_dump($firstPlayerId);
        var_dump($inviteCode);*/


        $playerModel = new PlayerModel();

        $secondPlayer = $playerModel
            ->clear()
            ->query()
            ->select('*')
            //TODO можно подправить where2 чтобы не писать :именованный плейсхолдер
            ->where2('code', '=', ':code')
            //->getQuery());
            ->fetch(
                [
                    'code' => $currentGame->getInviteCode()
                ]
            );

        var_dump($secondPlayer);

        $gameFieldModel = new GameFieldModel();


        /**
         * @var $firstPlayerGameField GameFieldEntity
         */
        $firstPlayerGameField = $gameFieldModel
            ->query()
            ->where2('game_id', '=', ':gameId')
            ->where2('player_id', '=', ':playerId')
            ->select('*')
            //->getQuery();
            ->fetch(
                [
                    'gameId' => $currentGame->getId(),
                    'playerId' => $currentGame->getPlayerId()
                ]
            );

        var_dump($firstPlayerGameField);

        /**
         * @var $secondPlayerGameField GameFieldEntity
         */
        $secondPlayerGameField = $gameFieldModel
            ->clear()
            ->query()
            ->where2('game_id', '=', ':gameId')
            ->where2('player_id', '=', ':playerId')
            ->select('*')
            ->fetch(
                [
                    'gameId' => $currentGame->getId(),
                    'playerId' => $secondPlayer->getId()
                ]
            );

        var_dump($secondPlayerGameField);

        $firstPlayerShipPlacement = new ShipPlacementModel();

        //TODO нормально ли тут исползовать такой метод без конвертации
        // либо можно использовать здесь QueryBuilder, так как если
        // его вызвать тут, то ему не передастся класс сущности к которой нужно конвертировать
        echo '<br>';
        var_dump($firstPlayerShipPlacement
            ->query()
            ->where2('game_field_id', '=', ':id')
            ->selectRow('select count(*) as placed')
            //->getQuery());
            //->fetchAll());
            ->fetchAllToArray(['id' => $firstPlayerGameField->getGameId()]));

        //TODO сделать класс Resoure, там toArray приведение к массиву разными классами

        /*$shipPlacementModel = new ShipPlacementModel();

        $shipPlacementModel
            ->query()
            ->select()*/

        $fieldMy = $fieldEnemy = array_fill(
            0,
            10,
            array_fill(0, 10, ['empty', 0])
        );


        /*foreach ($fieldMy as $key => $field) {
            echo $key;
            foreach ($field as $key => $item) {
                echo $key;
                print_r($item);
            }

        }*/
        //print_r($fieldMy);
        //$fieldEnemy = [10][10];
        $usedPlaces = [];


        //JsonUtil::makeAnswer($currentGame);


        //echo "here i am getStatus<br>";
        //var_dump($_POST);

        /*        $json = {
                "game":
                {
                    "id":"9",
                    "status":"1",
                    "invite":"aeGt81HUEn",
                    "myTurn":false,
                    "meReady":true
                },
                "fieldMy":[["hidden"...],...],
                "fieldEnemy":[["hidden", ...],],
                "usedPlaces":[],
                "success":true
                }*/


        for ($i = 0; $i < 10; $i++) {
            for ($j = 0; $j < 10; $j++) {
                $fields[$i][$j] = ['empty', 0];
            }
        }

        $json = [
            'game' =>
                [
                    'id' => $gameId,
                    'status' => 1,
                    'invite' => 4321,
                    'myTurn' => false,
                    'meReady' => false
                ],
            'fieldMy' => $fields,
            'fieldEnemy' => $fields,
            'usedPlaces' => [],
            'success' => true
        ];

        //JsonUtil::makeAnswer($json);

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

}