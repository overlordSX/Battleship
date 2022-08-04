<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Database\Model\ShipModel;
use Battleship\App\Validator\RuleInterface;

class IsShipExist implements RuleInterface
{

    /** @throws \Exception */
    public function pass($value): bool
    {
        $shipName = $value;

        return ShipModel::getInstance()->isShipExist($shipName);
    }

    public function message(): string
    {
        return 'Такого корабля не существует';
    }
}