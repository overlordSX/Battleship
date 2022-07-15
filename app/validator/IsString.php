<?php

class IsString implements RuleInterface
{

    public function pass($value): bool
    {
        return !is_null($value) and is_string($value) and !is_numeric($value);
    }

    public function message(): string
    {
        return "Вы ввели только цифры";
    }
}