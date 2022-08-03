<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\ShotModel;
use Battleship\App\Validator\Request\MakeShotRequest;

class ShotController implements ControllerInterface
{
    /**
     * @throws \Exception
     */
    public function makeShot(int $gameId, string $playerCode)
    {
        $makeShotRequest = new MakeShotRequest();
        $makeShotRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);
        $requestAnswer = $makeShotRequest->answer();

        if ($requestAnswer) {
            JsonUtil::makeAnswer($requestAnswer);
        }

        $shotModel = new ShotModel();
        $success = $shotModel->makeShot($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }

}