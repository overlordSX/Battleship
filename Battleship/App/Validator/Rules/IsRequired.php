<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsRequired implements RuleInterface
{
    public function __construct(protected string $paramName)
    {
    }

    public function pass($value): bool
    {
        return isset($value) && ((string) $value != false || (int) $value != false || (array) $value != false);
    }

    public function message(): string
    {
        return $this->paramName . ' является обязательным параметром';
    }
}