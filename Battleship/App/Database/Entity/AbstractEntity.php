<?php

namespace Battleship\App\Database\Entity;


abstract class AbstractEntity
{
    protected ?int $id;
    abstract public function getId();
}