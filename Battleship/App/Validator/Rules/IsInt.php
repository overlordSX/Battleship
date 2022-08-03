<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsInt implements RuleInterface
{
    public function __construct(protected string $paramName)
    {
    }

    public function pass($value): bool
    {
        return is_int($value);
    }

    public function message(): string
    {
        return $this->paramName . ', должен быть целым.';
    }
}