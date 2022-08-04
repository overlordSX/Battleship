<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Helpers\PrepareFieldScope;
use Battleship\App\Validator\RuleInterface;
use Exception;

class IsNoShipsIntersection implements RuleInterface
{

    /** @throws Exception */
    public function pass($value): bool
    {
        $field = $value['field'];

        $isHorizontal = $value['isHorizontal'];
        $shipSize = $value['shipSize'];
        $shipName = $value['shipName'];
        $shipX = $value['shipX'];
        $shipY = $value['shipY'];

        $fieldScope = PrepareFieldScope::prepare($shipX, $shipY, $isHorizontal, $shipSize);

        $startX = $fieldScope['startX'];
        $endX = $fieldScope['endX'];
        $startY = $fieldScope['startY'];
        $endY = $fieldScope['endY'];

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
        return "Пересечение с другим кораблем.\nВокруг корабля должна быть свободна одна клетка.";
    }
}