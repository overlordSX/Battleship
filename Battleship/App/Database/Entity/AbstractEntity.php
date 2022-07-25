<?php

abstract class AbstractEntity
{
    protected ?int $id;
    abstract public function getId();
}