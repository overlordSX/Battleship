<?php

class ShipEntity extends AbstractEntity
{
    protected ?int $id;
    protected string $name;
    protected int $size;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->data['name'];
    }


    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->data['size'];
    }
}