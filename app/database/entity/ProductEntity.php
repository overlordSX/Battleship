<?php


/**
 * Класс описывающий сущность продукта
 */
class ProductEntity extends AbstractEntity
{

    protected string $name;
    protected string $description;
    protected int $price;
    protected ?int $id;
    protected int $quantityOfComments = 0;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];

        $this->id = $data['id'] ?? null;

        $this->quantityOfComments = $data['comments'] ?? 0;
    }

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

    public function getQuantityOfComments(): mixed
    {
        return $this->quantityOfComments;
    }

}
