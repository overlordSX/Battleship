<?php

namespace Battleship\App\Helpers;

class PrepareShipSize
{
    public static function prepare(string $shipName): int
    {
        return (int)substr($shipName, 0, 1);
    }
}