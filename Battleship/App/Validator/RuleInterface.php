<?php

namespace Battleship\App\Validator;

interface RuleInterface
{
    public function pass($value): bool;
    public function message(): string;
}