<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Validator\Request\BaseRequest;
use Battleship\App\Validator\Request\PlayerReadyRequest;
use Exception;

class GameController implements ControllerInterface
{
    /** @throws Exception */
    public function startNewGame(): void
    {
        $newGame = new GameModel();
        $data = $newGame->start();

        JsonUtil::makeAnswer($data);
    }

    /** @throws Exception */
    public function getStatus(int $gameId, string $playerCode): void
    {
        $statusRequest = new BaseRequest();
        $statusRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);
        $requestValidationAnswer = $statusRequest->answer();

        if ($requestValidationAnswer) {
            JsonUtil::makeAnswer($statusRequest->answer());
        }

        $gameModel = new GameModel();
        $info = $gameModel->getInfo($gameId, $playerCode);

        JsonUtil::makeAnswer($info);
    }

    /** @throws Exception */
    public function setReady(int $gameId, string $playerCode): void
    {
        $playerReadyRequest = new PlayerReadyRequest();
        $playerReadyRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);
        $requestAnswer = $playerReadyRequest->answer();

        if ($requestAnswer) {
            JsonUtil::makeAnswer($playerReadyRequest->answer());
        }

        $gameModel = new GameModel();
        $ready = $gameModel->playersReady($gameId, $playerCode);

        JsonUtil::makeAnswer($ready);
    }
}