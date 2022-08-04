<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Validator\Request\BaseShipPlacementRequest;
use Battleship\App\Validator\Request\PlaceShipRequest;
use Exception;

class PlacementController implements ControllerInterface
{
    /**
     * @throws Exception
     */
    public function placeShip(int $gameId, string $playerCode)
    {
        $placeShipRequest = new PlaceShipRequest();
        $placeShipRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);

        $shipPlacementModel = new ShipPlacementModel();
        $success = $shipPlacementModel->makePlacement($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }

    /**
     * @throws Exception
     */
    public function clearField(int $gameId, string $playerCode)
    {
        $clearFieldRequest = new BaseShipPlacementRequest();
        $clearFieldRequest->validate(['gameId' => $gameId, 'playerCode' => $playerCode]);
        $requestAnswer = $clearFieldRequest->answer();

        if ($requestAnswer) {
            JsonUtil::makeAnswer($requestAnswer);
        }

        $field = new ShipPlacementModel();
        $success = $field->clearField($gameId, $playerCode);

        JsonUtil::makeAnswer($success);
    }
}