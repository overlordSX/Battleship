<?php


/**
 * Класс описывающий сущность отзыва
 */
class CommentEntity
{

    public function __construct(
        protected string $email,
        protected string $comment,
        protected int $product_id,
        protected ?int $id
    ) { }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }

}