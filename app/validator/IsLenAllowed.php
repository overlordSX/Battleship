<?php

class IsLenAllowed implements RuleInterface
{
    public function __construct(protected int $maxLen){}

    public function pass($value): bool
    {
        return strlen($value ?? '') <= $this->maxLen ;
    }

    public function message(): string
    {
        return "Вы ввели слишком много символов";
    }
}