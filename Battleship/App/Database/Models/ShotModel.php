<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\ShotEntity;

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
    public function makeShot($gameId, $playerCode): array
    {
        $success = [];

        $coordinateX ??= $_POST['x'];
        $coordinateY ??= $_POST['y'];

        $gameModel = new GameModel();
        $currentGame = $gameModel->getGameById($gameId);

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        //TODO где должны быть проверки?

        // проверка на очередь
        if (!$playerModel->isMyTurn($currentGame, $currentPlayer)) {
            return ['success' => false, 'message' => 'Дождитесь своей очереди'];
        }

        $enemyPlayer = $playerModel->getEnemyPlayer($currentGame, $currentPlayer);

        $gameFieldModel = new GameFieldModel();
        $enemyGameField = $gameFieldModel->getByGameAndPlayer($gameId, $enemyPlayer->getId());

        //TODO нужно проверка на то что сюда уже стреляли
        $success['success'] = $this->realize($coordinateX, $coordinateY, $enemyGameField->getId());

        //TODO выстрел уже произошел, теперь нужно посмотреть попал или нет
        // в enemyField придет с отмеченным выстрелом уже
        $enemyShips = new ShipPlacementModel();
        $enemyShips->getFieldAndUsedPlaces($enemyGameField->getId());
        $enemyField = $enemyShips->getField();

        if (!$enemyShips->isHereShip($coordinateX, $coordinateY)) {
            $gameModel->changeTurn($gameId);
            return $success;
        }

        $enemyShip = $enemyShips->getShip($coordinateX, $coordinateY);

        $success['hit'] = true;

        $shipName = $enemyShip->getName();
        $shipSize = $enemyShip->getSize();

        if ($shipSize === 1) {

            $success['success'] = $this->fillShots($coordinateX, $coordinateY, $shipSize, $enemyGameField->getId());
            $success['kill'] = true;
            return $success;
        }

        $startCell = $enemyShips->getStartCell($enemyShip);
        $firstX = $startCell['firstX'];
        $firstY = $startCell['firstY'];
        $isHorizontal = $startCell['orientation'];

        $shotPartsCount = 0;

        if ($isHorizontal) {
            for ($i = 0; $i < $shipSize; $i++) {
                if ($firstX + $i >= 0 && $firstX + $i <= 9 && $firstY >= 0 && $firstY <= 9) {
                    $cell = $enemyField[$firstX + $i][$firstY];

                    if ($cell[0] === $shipName && $cell[1] !== 1) {
                        return $success;
                    }

                    if ($cell[0] === $shipName && $cell[1] === 1) {
                        $shotPartsCount++;
                    }
                }
            }
        } else {
            for ($i = 0; $i < $shipSize; $i++) {

                $cell = $enemyField[$firstX][$firstY + $i];

                if ($cell[0] === $shipName && $cell[1] !== 1) {
                    return $success;
                }

                if ($cell[0] === $shipName && $cell[1] === 1) {
                    $shotPartsCount++;
                }
            }
        }

        if ($shotPartsCount === $shipSize) {
            $success['success'] = $this->fillShots($firstX, $firstY, $shipSize, $enemyGameField->getId(), $isHorizontal);
            $success['kill'] = true;

            return $success;
        }

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
        //TODO + ориентация
        $width = $isHorizontal ? $shipSize : 1;
        $height = $isHorizontal ? 1 : $shipSize;

        for ($x = $coordinateX - 1; $x <= $coordinateX + $width; $x++) {
            for ($y = $coordinateY - 1; $y <= $coordinateY + $height; $y++) {
                if ($x >= 0 && $x <= 9 && $y >= 0 && $y <= 9) {
                    if (!$this->realize($x, $y, $gameFieldId)) {
                        return false;
                    }
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
        //TODO по сути у меня нет никакой проверки на то что выстрел произойдет в ту же координату,
        // и если в эту функцию придут координаты по которым уже был выстрел, то он будет добавлен
        // так как нет ограничения на уникальность координат
        return $this->insert([
            'coordinate_x' => $coordinateX,
            'coordinate_y' => $coordinateY,
            'game_field_id' => $gameFieldId,
        ]);
    }

}