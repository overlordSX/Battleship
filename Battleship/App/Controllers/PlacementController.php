<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\ShipPlacementModel;

class PlacementController implements ControllerInterface
{
    /**
     * @throws \Exception
     */
    public function placeShip(int $gameId, string $playerCode)
    {
        $shipPlacementModel = new ShipPlacementModel();
        $success = $shipPlacementModel->placeShip($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }

    /**
     * @throws \Exception
     */
    public function clearField($gameId, $playerCode)
    {
        $field = new ShipPlacementModel();
        $success = $field->clearField($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }
}