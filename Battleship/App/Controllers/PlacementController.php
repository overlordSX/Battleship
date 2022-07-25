<?php

namespace Battleship\App\Controllers;


use Battleship\App\Controllers\Util\JsonUtil;
use Battleship\App\Database\Entity\PlayerEntity;
use Battleship\App\Database\Model\GameFieldModel;
use Battleship\App\Database\Model\GameModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShipModel;
use Battleship\App\Database\Model\ShipPlacementModel;

class PlacementController implements ControllerInterface
{
    /**
     * @throws \Exception
     */
    public function placeShip(int $gameId, string $playerCode)
    {

        $coordinateX ??= $_POST['x']; //int, 0 <= x <= 9,
        $coordinateY ??= $_POST['y']; //int, 0 <= y <= 9,

        $shipName ??= $_POST['ship']; // тип-номер корабля можно будет проверить regex ^[1-4]-[1-4]$

        //TODO из за того что непонятно откуда берется запятая с фронта
        $shipName = substr($shipName, 0, 3);
        $orientation ??= $_POST['orientation']; // ^(vertical|horizontal)$


        if (
            isset($_POST['orientation'])
            and (
                $_POST['orientation'] === 'horizontal'
                or $_POST['orientation'] === 'vertical'
            )
        ) {
            $orientation = ($_POST['orientation'] === 'horizontal');
        }

        $oldOrientation = !$orientation;

        $gameModel = new GameModel();
        $currentGame = $gameModel->getGameById($gameId);


        //TODO всю игру потому что по id игрока запрос быстрее будет, плюс надо будет проверять что статус игры == 1


        $playerModel = new PlayerModel();

        $currentPlayer = $playerModel->getPlayerByCode($playerCode);


        $gameFieldModel = new GameFieldModel();
        $currentGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());


        $shipModel = new ShipModel();
        $currentShip = $shipModel->getByName($shipName);


        //TODO РАЗВОРОТ КОРАБЛЯ
        // сначала надо проверить стоит ли уже такой корабль:
        // если стоит -> сравнить ориентации,
        //      если разные -> поменять ориентацию, но при смене ориентации нужно еще проверять не пересекаются ли корабли
        //      потом после проверки на пересечение с другими кораблями обновить данные в таблице
        //      если одинаковые -> ничего не делать
        // если не стоит -> поставить.

        $shipPlacementModel = new ShipPlacementModel();

        $isAlreadyPlaced = $shipPlacementModel->query()
            ->where('game_field_id', '=', $currentGameField->getId())
            ->where('ship_id', '=', $currentShip->getId())
            ->selectCountRows()
            ->fetchCount();

        if ($isAlreadyPlaced) {
            $success['success'] = $shipPlacementModel
                ->update('orientation', '=', $oldOrientation, $orientation)
                ->where('game_field_id', '=', $currentGameField->getId())
                ->where('ship_id', '=', $currentShip->getId())
                ->prepareAndExecute();
        } else {
            $success['success'] = $shipPlacementModel
                ->insert([
                    'coordinate_x' => $coordinateX,
                    'coordinate_y' => $coordinateY,
                    'orientation' => $orientation,
                    'ship_id' => $currentShip->getId(),
                    'game_field_id' => $currentGameField->getId()
                ]);
        }



        JsonUtil::makeAnswer($success);

    }

    /**
     * @throws \Exception
     */
    public function clearField($gameId, $currentPlayerCode)
    {
        $playerModel = new PlayerModel();

        $currentPlayer = $playerModel->getPlayerByCode($currentPlayerCode);


        $gameFieldModel = new GameFieldModel();
        $currentGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());


        $shipPlacementModel = new ShipPlacementModel();

        $success['success'] = $shipPlacementModel
            ->delete('game_field_id', '=', $currentGameField->getId());

        JsonUtil::makeAnswer($success);
    }
}