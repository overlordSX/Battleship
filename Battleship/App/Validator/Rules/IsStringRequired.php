<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsStringRequired implements RuleInterface
{

    public function pass($value): bool
    {
        return is_string($value) && (string)$value != '';
    }

    public function message(): string
    {
        return 'Вы отправили пустую строку';
    }
}