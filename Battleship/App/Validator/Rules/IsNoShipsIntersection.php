<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Validator\RuleInterface;

class IsNoShipsIntersection implements RuleInterface
{

    /** @throws \Exception */
    public function pass($value): bool
    {
        $ship = $value['ship'];
        $field = $value['field'];

        $isHorizontal = ($ship['orientation'] === 'horizontal');
        $shipSize = substr($ship['ship'], 0, 1);
        $shipX = (int)$ship['x'];
        $shipY = (int)$ship['y'];
        $shipName = (string)$ship['ship'];

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
                if ($field[$x][$y][0] !== ShipPlacementModel::EMPTY_CELL_NAME && $field[$x][$y][0] !== $shipName) {
                    return false;
                }
            }
        }

        return true;
    }

    public function message(): string
    {
        return "Вы не можете поставить сюда корабль.\nВокруг корабля должна быть свободна одна клетка.";
    }
}