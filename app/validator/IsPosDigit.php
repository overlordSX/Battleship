<?php

class IsPosDigit implements RuleInterface
{

    public function pass($value): bool
    {
        return is_numeric($value) and $value > 0;
    }

    public function message(): string
    {
        return 'Цена должна быть числом, больльше 0';
    }
}