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
        //TODO должна быть проверка что сейчас 2й статус игры + игрок не нажимал что готов
        //TODO хотя если отправить только название корабля, то он должен исчезнуть с поля
        //TODO сделать класс Resource, там toArray приведение к массиву разными классами, вроде это про JSON
        // это про ship-placement
        // в двух вариантах

        //Validator::make([data],[rules])
        //Validator::getMessages ?empty? -> Json else дальше

        $shipPlacementModel = new ShipPlacementModel();
        $success = $shipPlacementModel->makePlacement($gameId, $playerCode);

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