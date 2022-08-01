<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsPosInt implements RuleInterface
{

    public function pass($value): bool
    {
        return is_int($value) && $value > 0;
    }

    public function message(): string
    {
        return 'Код игры должен быть целым, положительным числом';
    }
}