<?php

namespace Battleship\App\Database\Model;

use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Entity\ShipPlacementEntity;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Аттрибуты:
 * coordinate_x,
 * coordinate_y,
 * orientation,
 * ship_id,
 * game_field_id
 */
class ShipPlacementModel extends AbstractModel
{

    protected string $tableName = 'ship_placement';
    protected string $entityClassName = ShipPlacementEntity::class;


    /**
     * @throws \Exception
     */
    public function placeShip($gameId, $playerCode): array
    {
        //TODO должна быть проверка что сейчас 2й статус игры + игрок не нажимал что готов

        $success = [];

        //TODO хотя если отправить только название корабля, то он должен исчезнуть с поля
        if (isset($_POST['x']) || isset($_POST['y']) || isset($_POST['ship']) || ($_POST['orientation'])) {
            $success['success'] = false;
            $success['message'] = 'не переданы все параметры';
        }

        $coordinateX ??= (int)$_POST['x']; //int, 0 <= x <= 9,
        $coordinateY ??= (int)$_POST['y']; //int, 0 <= y <= 9,

        $shipName ??= $_POST['ship']; // тип-номер корабля можно будет проверить regex ^[1-4]-[1-4]$

        //TODO из за того что непонятно откуда берется запятая с фронта
        $shipName = substr($shipName, 0, 3);


        //TODO сделать класс Resource, там toArray приведение к массиву разными классами, вроде это про JSON
        // это про ship-placement
        // в двух вариантах


        $orientation ??= $_POST['orientation']; // ^(vertical|horizontal)$

        $isValid = $_POST['orientation'] === 'horizontal' || $_POST['orientation'] === 'vertical';

        if (isset($_POST['orientation']) && $isValid) {
            $orientation = ($_POST['orientation'] === 'horizontal');
        }
        $oldOrientation = !$orientation;

        $gameModel = new GameModel();
        $currentGame = $gameModel->getGameById($gameId);

        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $gameFieldModel = new GameFieldModel();
        $currentGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());

        $shipModel = new ShipModel();
        $currentShip = $shipModel->getByName($shipName);

        //TODO РАЗВОРОТ КОРАБЛЯ
        // сначала надо проверить стоит ли уже такой корабль:
        // если стоит
        // -> сравнить ориентации
        //      если разные -> поменять ориентацию, но при смене ориентации нужно еще проверять не пересекаются ли корабли
        //      потом после проверки на пересечение с другими кораблями обновить данные в таблице
        //      если одинаковые -> ничего не делать
        // -> сравнить координаты
        //      разные -> поставить новые
        //      одинаковые -> ничего
        // если не стоит -> поставить.

        $isAlreadyPlaced = $this->query()
            ->where('game_field_id', '=', $currentGameField->getId())
            ->where('ship_id', '=', $currentShip->getId())
            ->fetchCount();

        if ($isAlreadyPlaced) {
            //TODO получается тут будет проверка на пересечение
            $placedShip = $this->getPlacedShip($currentGameField->getId(), $currentShip->getId());

            if ($placedShip->getOrientation() === $oldOrientation) {
                $success['success'] = $this
                    ->update(
                        [
                            ['game_field_id' => ['=' => $currentGameField->getId()]],
                            ['ship_id' => ['=' => $currentShip->getId()]]
                        ],
                        [['orientation' => $orientation]]
                    );
            } else {
                //TODO тут можно сообщить о том что фронт послал ту же ориентацию снова
                //$success['success'] = false;
            }

            $isCoordinateChanged = $placedShip->getCoordinateX() !== $coordinateX
                || $placedShip->getCoordinateY() !== $coordinateY;

            if ($isCoordinateChanged) {
                $success['success'] = $this
                    ->update(
                        [
                            ['game_field_id' => ['=' => $currentGameField->getId()]],
                            ['ship_id' => ['=' => $currentShip->getId()]]
                        ],
                        [
                            ['coordinate_x' => $coordinateX],
                            ['coordinate_y' => $coordinateY]
                        ]
                    );
            }
        } else {
            $success['success'] = $this
                ->insert([
                    'coordinate_x' => $coordinateX,
                    'coordinate_y' => $coordinateY,
                    'orientation' => $orientation,
                    'ship_id' => $currentShip->getId(),
                    'game_field_id' => $currentGameField->getId()
                ]);
        }

        return $success;
    }


    /**
     * @throws \Exception
     */
    #[ArrayShape(['success' => "bool"])]
    public function clearField($gameId, $playerCode): array
    {
        $playerModel = new PlayerModel();
        $currentPlayer = $playerModel->getPlayerByCode($playerCode);

        $gameFieldModel = new GameFieldModel();
        $currentGameField = $gameFieldModel->getByGameAndPlayer($gameId, $currentPlayer->getId());

        $shipPlacementModel = new ShipPlacementModel();
        return [
            'success' => $shipPlacementModel
                ->delete('game_field_id', '=', $currentGameField->getId())
        ];
    }


    /**
     * @param $gameFieldId
     * @return int
     * @throws \Exception
     */
    public function getShipsCount($gameFieldId): int
    {
        return $this
            ->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->fetchCount();
    }

    /**
     * @param $gameFieldId
     * @return array
     * @throws \Exception
     */
    #[ArrayShape(['usedPlaces' => "array", 'field' => "array"])]
    public function getFieldAndUsedPlaces($gameFieldId, bool $forEnemy = false): array
    {
        $usedPlaces = [];
        $field = $this->getEmptyPlacementArray();

        if ($this->getShipsCount($gameFieldId)) {

            $placedShips = $this->getPlacedShipsList($gameFieldId);

            foreach ($placedShips as $placedShip) {
                $x = $placedShip->getCoordinateX();
                $y = $placedShip->getCoordinateY();
                $isHorizontal = $placedShip->getOrientation();
                $name = $placedShip->getCustom('name');

                //TODO чтоб не париться с выстрелами
                //if (!$forEnemy) {
                $usedPlaces[] = $name;
                //$usedPlaces[] = $forEnemy ?: $name;


                if ($isHorizontal) {
                    for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                        $field[$x + $i][$y] =
                            [
                                [$name, 0]
                                //TODO потом, когда то, будет проверка на видимость, в зависимости от попадания
                            ];
                    }

                } else {
                    for ($i = 0; $i < $placedShip->getCustom('size'); $i++) {
                        $field[$x][$y + $i] =
                            [
                                [$name, 0]
                                //TODO потом, когда то, будет проверка на видимость, в зависимости от попадания
                            ];
                    }
                }

                //}
            }
        }
        return ['usedPlaces' => $usedPlaces, 'field' => $field];
    }


    /**
     * массив 10х10 со значениями ["empty", 0]
     * @return array [[["empty", 0],...]], [[...],... ]],... [[....],..]]]
     */
    protected function getEmptyPlacementArray(): array
    {
        return array_fill(0, 10, array_fill(0, 10, ['empty', 0]));
    }


    /**
     * @param $gameFieldId
     * @return ShipPlacementEntity[]
     * @throws \Exception
     */
    public function getPlacedShipsList($gameFieldId): array
    {
        return $this
            ->query()
            ->join('ship', 'ship_id', '=', 'id')
            ->where('game_field_id', '=', $gameFieldId)
            ->fetchAll();
    }


    /**
     * @param $gameFieldId
     * @param $shipId
     * @return ShipPlacementEntity
     * @throws \Exception
     */
    public function getPlacedShip($gameFieldId, $shipId): AbstractEntity
    {
        return $this->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->where('ship_id', '=', $shipId)
            ->fetch();
    }
}