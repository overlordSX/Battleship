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

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->price = $data['price'];
        if ($data['id'] !== null) {
            $this->id = $data['id'];
        } else {
            $this->id = null;
        }
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

}
