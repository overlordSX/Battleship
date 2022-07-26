<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\ShotModel;

class ShotController implements ControllerInterface
{
    /**
     * @throws \Exception
     */
    public function makeShot($gameId, $playerCode)
    {
        $shotModel = new ShotModel();
        $success = $shotModel->makeShot($gameId, $playerCode);

         JsonUtil::makeAnswer($success);
    }

}