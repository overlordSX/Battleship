<?php

namespace Battleship\App\Helpers;

use Battleship\App\Database\Model\ShipPlacementModel;
use JetBrains\PhpStorm\ArrayShape;

class PrepareFieldScope
{
    #[ArrayShape([
        'startX' => "int",
        'endX' => "int",
        'startY' => "int",
        'endY' => "int"
    ])]
    public static function prepare(int $shipX, int $shipY, bool $isHorizontal, int $shipSize): array
    {
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

        if ($endX > ShipPlacementModel::FIELD_EDGE_COORDINATE) {
            $endX = ShipPlacementModel::FIELD_EDGE_COORDINATE;
        }

        if ($endY > ShipPlacementModel::FIELD_EDGE_COORDINATE) {
            $endY = ShipPlacementModel::FIELD_EDGE_COORDINATE;
        }

        return [
            'startX' => $startX,
            'endX' => $endX,
            'startY' => $startY,
            'endY' => $endY
        ];
    }
}