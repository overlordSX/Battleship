<?php

class IsEmail implements RuleInterface
{
    public function pass($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function message(): string
    {
        return "Вы ввели неправильный Email.";
    }
}