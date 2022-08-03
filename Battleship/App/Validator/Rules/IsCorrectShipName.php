<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsCorrectShipName implements RuleInterface
{

    public function pass($value): bool
    {
        $pattern = '/^\d-\d$/';
        return preg_match($pattern,$value);
    }

    public function message(): string
    {
        return 'Название корабля указано неправильно';
    }
}