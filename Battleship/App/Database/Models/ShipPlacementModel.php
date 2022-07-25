<?php

namespace Battleship\App\Database\Model;

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
     * @param $gameFieldId
     * @return int
     * @throws \Exception
     */
    public function getShipsCount($gameFieldId): int
    {
        return $this
            ->query()
            ->where('game_field_id', '=', $gameFieldId)
            ->selectCountRows()
            ->fetchCount();
    }

    /**
     * @param $gameFieldId
     * @return array
     * @throws \Exception
     */
    #[ArrayShape(['usedPlaces' => "array", 'field' => "array"])]
    public function getFieldAndUsedPlaces($gameFieldId): array
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

                $usedPlaces[] = $name;

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
            ->select()
            ->fetchAll();
    }
}