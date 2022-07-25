<?php

namespace Battleship\App\Validator;

interface RuleInterface
{
    public function pass($value);
    public function message();
}