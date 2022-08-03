<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsCorrectLastTime implements RuleInterface
{

    public function pass($value): bool
    {
        return (is_int($value) && $value >= 0 && $value <= time()) || is_null($value);
    }

    public function message(): string
    {
        return 'Время отправки последнего сообщения должно быть от 0 до текущего времени';
    }
}