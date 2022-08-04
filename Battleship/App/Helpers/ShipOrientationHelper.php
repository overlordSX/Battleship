<?php

namespace Battleship\App\Helpers;

use Battleship\App\Database\Model\ShipPlacementModel;

class ShipOrientationHelper
{
    public static function isHorizontalFromString(string $orientation): bool
    {
        return $orientation === ShipPlacementModel::HORIZONTAL_SHIP_ORIENTATION;
    }
}