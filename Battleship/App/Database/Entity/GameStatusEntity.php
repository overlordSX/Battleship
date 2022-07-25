<?php

namespace Battleship\App\Database\Entity;

class GameStatusEntity extends AbstractEntity
{
    protected ?int $id;
    protected int $status;
    protected string $description;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}