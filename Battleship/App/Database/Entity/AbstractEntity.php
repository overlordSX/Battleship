<?php

namespace Battleship\App\Database\Entity;


abstract class AbstractEntity
{
    protected array $data;
    abstract public function getId();
}