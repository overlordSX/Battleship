<?php


/**
 * Класс описывающий сущность продукта
 */
class ProductEntity
{

    public function __construct(
        protected string $name,
        protected string $description,
        protected int $price,
        protected ?int $id
    ) { }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

}
