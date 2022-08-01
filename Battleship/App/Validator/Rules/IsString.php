<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsString implements RuleInterface
{

    public function pass($value): bool
    {
        return !is_null($value) and is_string($value);
    }

    public function message(): string
    {
        return 'Это не строка';
    }
}