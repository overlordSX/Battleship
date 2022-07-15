<?php

class IsRequired implements RuleInterface
{
    public function __construct(protected bool $required)
    { }

    public function pass($value): bool
    {
        return (!$this->required or isset($value)) and !empty($value);
    }

    public function message(): string
    {
        return 'Это поле является обязательным';
    }
}