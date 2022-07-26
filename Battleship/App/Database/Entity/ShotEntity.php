<?php

namespace Battleship\App\Database\Entity;

/**
 * Аттрибуты:
 * coordinate_x,
 * coordinate_y,
 * game_field_id
 */
class ShotEntity extends AbstractEntity
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
    public function getCoordinateX(): int
    {
        return $this->data['coordinate_x'];
    }

    /**
     * @return int
     */
    public function getCoordinateY(): int
    {
        return $this->data['coordinate_y'];
    }

    /**
     * @return int
     */
    public function getGameFieldId(): int
    {
        return $this->data['game_field_id'];
    }
}