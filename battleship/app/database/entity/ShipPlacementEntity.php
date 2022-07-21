<?php

class ShipPlacementEntity extends AbstractEntity
{
    protected ?int $id;
    protected int $coordinate_x;
    protected int $coordinate_y;
    protected bool $orientation;
    protected int $shipId;
    protected int $gameFieldId;

    public function __construct(array $row)
    {
        $this->id = $row['id'] ?? null;
        $this->coordinate_x = $row['coordinate_x'];
        $this->coordinate_y = $row['coordinate_y'];
        $this->orientation = $row['orientation'];
        $this->shipId = $row['ship_id'];
        $this->gameFieldId = $row['game_field_id'];
    }

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
    public function getOrientation(): int
    {
        return $this->orientation;
    }

    /**
     * @return int
     */
    public function getShipId(): int
    {
        return $this->shipId;
    }

    /**
     * @return int
     */
    public function getGameFieldId(): int
    {
        return $this->gameFieldId;
    }
}