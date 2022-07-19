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
    public function startGame(): void
    {




        $data =
            [
                'id' => '1',
                'code' => '1234',
                'invite' => '4321',
                'success' => true
            ];

        JsonUtil::makeAnswer($data);

        //http_redirect('/placement/' . $data['id'] . '/' . $data['code'] . '/');
        /*header('Location: ' . '/placement/' . $data['id'] . '/' . $data['code'] . '/');*/
        //die();
    }

    public function getStatus($gameId, $playerCode)
    {
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

        $fields = [[]];
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

        header('Content-Type: application/json');
        echo json_encode($json);
        die();


        /*//http_redirect('/placement/' . $data['id'] . '/' . $data['code'] . '/');
        header('Location: ' . '/placement/' . $gameId . '/' . $playerCode . '/');
        exit();*/

    }

    public
    function setStatus()
    {
        echo "here i am setStatus<br>";
    }

}