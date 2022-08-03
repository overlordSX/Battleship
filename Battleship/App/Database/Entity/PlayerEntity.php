<?php

namespace Battleship\App\Database\Entity;

class PlayerEntity extends AbstractEntity
{
    protected array $data;

    public function __construct(array $row)
    {
        $this->data = $row;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->data['id'];
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->data['code'];
    }
}