<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsCorrectOrientation implements RuleInterface
{

    public function pass($value): bool
    {
        $pattern = '/^(horizontal|vertical)$/';

        return preg_match($pattern, $value);
    }

    public function message(): string
    {
        return 'Ориентация может быть только горизонтальной или вертикальной';
    }
}