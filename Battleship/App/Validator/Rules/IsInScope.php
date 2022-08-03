<?php

namespace Battleship\App\Validator\Rule;

use Battleship\App\Validator\RuleInterface;

class IsInScope implements RuleInterface
{
    /**
     * от min до max, включительно
     * @param int $min
     * @param int $max
     */
    public function __construct(protected int $min, protected int $max)
    {
    }

    public function pass($value): bool
    {
        return $value >= $this->min && $value <= $this->max;
    }

    public function message(): string
    {
        return 'Выход за рамки поля';
    }
}