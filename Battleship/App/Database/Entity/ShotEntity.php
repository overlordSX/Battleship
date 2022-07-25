<?php

namespace Battleship\App\Database\Entity;

class ShotEntity extends AbstractEntity
{
    protected ?int $id;
    protected int $coordinate_x;
    protected int $coordinate_y;
    protected int $gameFieldId;



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
    public function getCoordinateX(): int
    {
        return $this->coordinate_x;
    }

    /**
     * @return int
     */
    public function getCoordinateY(): int
    {
        return $this->coordinate_y;
    }

    /**
     * @return int
     */
    public function getGameFieldId(): int
    {
        return $this->gameFieldId;
    }
}