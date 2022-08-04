<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\ShipPlacementModel;
use Battleship\App\Validator\RuleInterface;

class IsCorrectOrientation implements RuleInterface
{

    public function pass($value): bool
    {
        return $value === ShipPlacementModel::HORIZONTAL_SHIP_ORIENTATION
            || $value === ShipPlacementModel::VERTICAL_SHIP_ORIENTATION;
    }

    public function message(): string
    {
        return 'Ориентация может быть только горизонтальной или вертикальной';
    }
}