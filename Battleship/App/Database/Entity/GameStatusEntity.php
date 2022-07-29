<?php

namespace Battleship\App\Database\Entity;

/**
 * Аттрибуты status, description
 */
class GameStatusEntity extends AbstractEntity
{
    protected ?int $id;

    protected array $data;

    public function __construct($row)
    {
        $this->data = $row;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->data['id'];
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->data['status'];
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->data['description'];
    }
}