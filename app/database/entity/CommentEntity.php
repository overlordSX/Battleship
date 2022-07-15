<?php


/**
 * Класс описывающий сущность отзыва
 */
class CommentEntity extends AbstractEntity
{
    protected string $email;
    protected string $comment;
    protected int $product_id;
    protected ?int $id;
    protected bool $activity_status;

    public function __construct($data)
    {
        $this->email      = $data['email'];
        $this->comment    = $data['comment'];
        $this->product_id = $data['product_id'];

        $this->id = $data['id'] ?? null;

        $this->activity_status = $data['activity_status'] ?? false;
    }

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

    public function getActivityStatus(): bool
    {
        return $this->activity_status;
    }

}