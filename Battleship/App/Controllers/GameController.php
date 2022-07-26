<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\GameModel;
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
        $newGame = new GameModel();
        $data = $newGame->start();

        JsonUtil::makeAnswer($data);
    }

    /**
     * возвращает статус игры
     *
     * @param $gameId
     * @param $playerCode
     * @throws Exception
     */
    public function getStatus($gameId, $playerCode): void
    {
        $gameModel = new GameModel();
        $info = $gameModel->getInfo($gameId, $playerCode);

        JsonUtil::makeAnswer($info);
    }

    /**
     * @throws Exception
     */
    public function setReady($gameId, $playerCode): void
    {
        $gameModel = new GameModel();
        $ready = $gameModel->playersReady($gameId, $playerCode);

        JsonUtil::makeAnswer($ready);
    }


}