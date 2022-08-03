<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IfWasErrorsStop implements RuleInterface
{

    public function pass($value): bool
    {
        return false;
    }

    public function message(): string
    {
        return 'Это правило останавливает дальнейшние проверки валидатора';
    }
}