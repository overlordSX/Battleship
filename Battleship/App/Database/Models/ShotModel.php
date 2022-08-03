<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\ShipPlacementEntity;
use Battleship\App\Database\Entity\ShotEntity;

class ShotModel extends AbstractModel
{

    public const SHOT_DOWN = 1;
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

    /** @throws \Exception */
    public function makeShot($gameId, $playerCode): array
    {
        $success = [];

        $coordinateX = isset($_POST['x'])
            ? (int)htmlspecialchars($_POST['x'], ENT_QUOTES) : null;
        $coordinateY = isset($_POST['y'])
            ? (int)htmlspecialchars($_POST['y'], ENT_QUOTES) : null;

        $gameModel = new GameModel();
        $currentGame = $gameModel->getGameById($gameId);

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $enemyPlayer = $playerModel->getEnemyPlayer($currentGame, $currentPlayer);

        $gameFieldModel = new GameFieldModel();
        $enemyGameField = $gameFieldModel->getByGameAndPlayer($gameId, $enemyPlayer->getId());

        $success['success'] = $this->realize($coordinateX, $coordinateY, $enemyGameField->getId());

        $enemyShips = new ShipPlacementModel();
        $enemyShips->fillFieldAndUsedPlaces($enemyGameField->getId());
        $enemyField = $enemyShips->getField();

        if (!$enemyShips->isHereShip($coordinateX, $coordinateY)) {
            $gameModel->changeTurn($gameId);
            return $success;
        }

        $enemyShip = $enemyShips->getShip($coordinateX, $coordinateY);

        $success['hit'] = true;

        $shipName = $enemyShip->getName();
        $shipSize = $enemyShip->getSize();

        $placedShips = $enemyShips->getShips();
        $shipsArrayPosition = [];
        if (count($placedShips) > 0) {
            foreach ($placedShips as $number => $placedShip) {
                $shipsArrayPosition[$placedShip->getCustom('name')] = $number;
            }
        }

        $shipOnField = $placedShips[$shipsArrayPosition[$shipName]];

        $shotPartsCount = $this->shotPartsCount($shipOnField, $enemyField);

        if ($shotPartsCount !== $shipSize) {
            return $success;
        }

        $success['success'] = $this->fillShots(
            $enemyField,
            $shipOnField,
            $enemyGameField->getId()
        );
        $success['kill'] = true;

        $diedShips = 0;
        foreach ($shipsArrayPosition as $shipName => $arrayPosition) {
            $shipOnField = $placedShips[$arrayPosition];
            $shotPartsCount = $this->shotPartsCount($shipOnField, $enemyField);
            if ($shotPartsCount === $shipOnField->getCustom('size')) {
                $diedShips++;
            }
        }

        if ($diedShips === ShipPlacementModel::SHIPS_ON_FIELD) {
            $success['victory'] = true;
            $gameModel->setGameStatus($gameId, GameModel::END_GAME_STATUS);
        }

        return $success;
    }

    protected function shotPartsCount(ShipPlacementEntity $shipOnField, $enemyField): int
    {
        $firstX = $shipOnField->getCoordinateX();
        $firstY = $shipOnField->getCoordinateY();
        $isHorizontal = $shipOnField->getOrientation();
        $shipSize = $shipOnField->getCustom('size');

        $shotPartsCount = 0;

        if ($isHorizontal) {
            for ($i = 0; $i < $shipSize; $i++) {
                //TODO с валидными данными, такая проверка не нужна
                if ($firstX + $i >= 0 && $firstX + $i <= ShipPlacementModel::FIELD_SIZE) {
                    $cell = $enemyField[$firstX + $i][$firstY];
                    if ($cell[1] !== self::SHOT_DOWN) {
                        return $shotPartsCount;
                    }
                    $shotPartsCount++;
                }
            }
        } else {
            for ($i = 0; $i < $shipSize; $i++) {
                //TODO с валидными данными, такая проверка не нужна
                if ($firstY + $i >= 0 && $firstY + $i <= ShipPlacementModel::FIELD_SIZE) {
                    $cell = $enemyField[$firstX][$firstY + $i];
                    if ($cell[1] !== self::SHOT_DOWN) {
                        return $shotPartsCount;
                    }
                    $shotPartsCount++;
                }
            }
        }
        return $shotPartsCount;
    }

    /**
     * Заполнит все выстрелами вокруг указанного типа корабля
     * @param $field
     * @param ShipPlacementEntity $ship
     * @param $gameFieldId
     * @return bool
     * @throws \Exception
     */
    public function fillShots($field, ShipPlacementEntity $ship, $gameFieldId): bool
    {
        $isHorizontal = $ship->getOrientation();
        $shipSize = $ship->getCustom('size');
        $shipX = $ship->getCoordinateX();
        $shipY = $ship->getCoordinateY();

        $startX = $shipX;
        $startY = $shipY;

        if ($startX > 0) {
            $startX -= 1;
        }

        if ($startY > 0) {
            $startY -= 1;
        }

        $width = $isHorizontal ? $shipSize : 1;
        $height = $isHorizontal ? 1 : $shipSize;

        $endX = $shipX + $width;
        $endY = $shipY + $height;
        if ($endX > ShipPlacementModel::FIELD_SIZE) {
            $endX = ShipPlacementModel::FIELD_SIZE;
        }

        if ($endY > ShipPlacementModel::FIELD_SIZE) {
            $endY = ShipPlacementModel::FIELD_SIZE;
        }

        for ($x = $startX; $x <= $endX; $x++) {
            for ($y = $startY; $y <= $endY; $y++) {
                if ($field[$x][$y][1] !== self::SHOT_DOWN) {
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
        return $this->insert([
            'coordinate_x' => $coordinateX,
            'coordinate_y' => $coordinateY,
            'game_field_id' => $gameFieldId,
        ]);
    }

}