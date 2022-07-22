<?php

class ShipPlacementEntity extends AbstractEntity
{
    protected ?int $id;
    protected int $coordinate_x;
    protected int $coordinate_y;
    protected bool $orientation;
    protected int $shipId;
    protected int $gameFieldId;

    protected array $data;

    public function __construct(array $row)
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
     * @return bool
     */
    public function getOrientation(): bool
    {
        return $this->data['orientation'];
    }

    /**
     * @return int
     */
    public function getShipId(): int
    {
        return $this->data['ship_id'];
    }

    /**
     * @return int
     */
    public function getGameFieldId(): int
    {
        return $this->data['game_field_id'];
    }

    public function getCustom(string $attribute): mixed
    {
        return $this->data[$attribute];
    }
}