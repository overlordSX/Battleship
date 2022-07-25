<?php

interface RuleInterface
{
    public function pass($value);
    public function message();
}