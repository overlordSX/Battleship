<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\ShotEntity;
use JetBrains\PhpStorm\ArrayShape;

class ShotModel extends AbstractModel
{

    protected string $tableName = 'shot';
    protected string $entityClassName = ShotEntity::class;

    /**
     * @param $gameFieldId
     * @return ShotEntity[]
     * @throws \Exception
     */
    public function getShotsArray($gameFieldId): array
    {
        $shots = $this
            ->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->select('id', 'coordinate_x', 'coordinate_y')
            ->fetchAll();

        $shotField = array_fill(0, 10, array_fill(0, 10, 0));

        foreach ($shots as $shot) {
            $x = $shot->getCoordinateX();
            $y = $shot->getCoordinateY();
            $shotField[$x][$y] = 1;
        }

        return $shotField;
    }

    /**
     * @throws \Exception
     */
    #[ArrayShape(['success' => "bool", 'hit' => "bool", 'kill' => "bool"])]
    public function makeShot($gameId, $playerCode): array
    {
        $success = [];

        $coordinateX ??= $_POST['x'];
        $coordinateY ??= $_POST['y'];

        $gameModel = new GameModel();
        $currentGame = $gameModel->getGameById($gameId);

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);
        $enemyPlayer = $playerModel->getEnemyPlayer($currentGame, $currentPlayer);

        $gameFieldModel = new GameFieldModel();
        $enemyGameField = $gameFieldModel->getByGameAndPlayer($gameId, $enemyPlayer->getId());

        $success['success'] = $this->realize($coordinateX, $coordinateY, $enemyGameField->getId());

        /*$shipPlacementModel = new ShipPlacementModel();
        var_dump($shipPlacementModel->query()
            ->join('shot','game_field_id','=','game_field_id')
            ->where('game_field_id','=',$enemyGameField->getId())
            ->fetchAllToArray());*/
        /*$shot = new QueryBuilder();
        JsonUtil::makeAnswer($shot->selectRow(
            'select *
                    from shot join ship_placement on shot.game_field_id = ship_placement.game_field_id
                    where shot.game_field_id = ' . $enemyGameField->getId() . ' and  ship_placement.game_field_id = ' . $enemyGameField->getId()
            . ' and shot.coordinate_x = ship_placement.coordinate_x and shot.coordinate_y = ship_placement.coordinate_y '
        )->fetchAllToArray());*/

        //TODO выстрел уже произошел, теперь нужно посмотреть попал или нет
        // в enemyField придет с отмеченным выстрелом уже
        $enemyShips = new ShipPlacementModel();
        $enemyShips->getFieldAndUsedPlaces($enemyGameField->getId());
        $enemyField = $enemyShips->getField();

        //$isHereShip = $enemyShips->isHereShip($coordinateX, $coordinateY);
        if (!$enemyShips->isHereShip($coordinateX, $coordinateY)) {
            //$success['success'] = $this->realize($coordinateX, $coordinateY, $enemyGameField->getId());
            $gameModel->changeTurn($gameId);
            return $success;
        }

        $success['hit'] = true;

        $shipName = $enemyField[$coordinateX][$coordinateY][0];
        $shipSize = (int)substr($shipName, 0, 1);

        if ($shipSize === 1) {

            $startFillX = $coordinateX;
            $startFillY = $coordinateY;


            if ($coordinateX > 0 && $coordinateX < 9) {
                $startFillX = $coordinateX - 1;
            }
            if ($coordinateY > 0 && $coordinateY < 9) {
                $startFillY = $coordinateY - 1;
            }

            $endX = $startFillX + $shipSize + 1;
            $endY = $startFillY + $shipSize + 1;

            for ($x = $startFillX; $x <= $endX; $x++) {
                for ($y = $startFillY; $y <= $endY; $y++) {
                    $success['success'] = $this->realize($x, $y, $enemyGameField->getId());
                }
            }

            //$success['success'] = $this->fillShots($coordinateX, $coordinateY,$shipSize, $enemyGameField->getId());

            $success['kill'] = true;
            return $success;
        }

        /*for ($i = 1; $i < $shipSize; $i++) {
            if ($enemyField[$coordinateX + $i][$coordinateY][0] !== $shipName) {
                //$direction =
                $success['success'] = false;
            } elseif ($enemyField[$coordinateX + $i][$coordinateY][0] !== $shipName)

        }*/

        $firstX = -1;
        $firstY = -1;
        for ($i = 1; $i < 10; $i++) {
            for ($j = 1; $j < 10; $j++) {
                if ($enemyField[$i][$j][0] === $shipName) {
                    /*$firstCell['x'] = $i;
                    $firstCell['y'] = $j;*/
                    $firstX = $i;
                    $firstY = $j;
                    break 2;
                }
            }
        }

        //TODO в данный момент success 'success' => true, 'hit' = true

        $isHorizontal = false;
        $count = 0;
        for ($i = 0; $i < $shipSize; $i++) {
            //$visibility = $firedShots[$x + $i][$y];
            $cell = $enemyField[$firstX + $i][$firstY];
            if ($cell[0] === $shipName && $cell[1] !== 1) {
                //$success['success'] = $this->realize($coordinateX, $coordinateY, $enemyGameField->getId());
                return $success;
            }
            $count++;
        }
        $count = 0;
        for ($i = 0; $i < $shipSize; $i++) {
            //$visibility = $firedShots[$x + $i][$y];
            $cell = $enemyField[$firstX][$firstY + $i];
            if ($cell[0] === $shipName && $cell[1] !== 1) {
                //$success['success'] = $this->realize($coordinateX, $coordinateY, $enemyGameField->getId());
                return $success;
            }
            $count++;
            $isHorizontal = true;
        }

        if ($count === $shipSize) {

            $success['success'] = $this->fillShots($firstX, $firstY, $shipSize, $enemyGameField->getId(), $isHorizontal);

            $success['kill'] = true;
            return $success;
        }


        //TODO ?
        // эталон отсылает hit:bool и еще может kill:bool
        // это же получается все нужно тут делать, и если убил поля вокруг корабля отмечать отстрелянными

        return $success;
    }

    /**
     * заполнит все выстрелами вокруг указанного типа корабля
     * @param $coordinateX
     * @param $coordinateY
     * @param $shipSize
     * @param $gameFieldId
     * @return void
     * @throws \Exception
     */
    public function fillShots($coordinateX, $coordinateY, $shipSize, $gameFieldId, $isHorizontal = true): bool
    {
        $startFillX = $coordinateX;
        $startFillY = $coordinateY;

        if ($coordinateX > 0 && $coordinateX < 9) {
            $startFillX = $coordinateX - 1;
        }
        if ($coordinateY > 0 && $coordinateY < 9) {
            $startFillY = $coordinateY - 1;
        }

        if ($isHorizontal) {
            $endX = $startFillX + $shipSize + 1;
            $endY = $startFillY + 1;
        } else {
            $endX = $startFillX + 1;
            $endY = $startFillY + $shipSize + 1;
        }

        if ($endX > 9) {
            $endX = 9;
        }
        if ($endY > 9) {
            $endY = 9;
        }


        for ($x = $startFillX; $x < $endX; $x++) {
            for ($y = $startFillY; $y < $endY; $y++) {
                if (!$this->realize($x, $y, $gameFieldId)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function realize(int $coordinateX, int $coordinateY, int $gameFieldId): bool
    {
        return $this->insert([
            'coordinate_x' => $coordinateX,
            'coordinate_y' => $coordinateY,
            'game_field_id' => $gameFieldId,
        ]);
    }

}