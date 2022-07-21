<?php

class PlayerEntity extends AbstractEntity
{
    protected ?int $id;
    protected string $code;

    //внутри можно хранить data[] со всеми параметрами


    public function __construct(array $row)
    {
        $this->id = $row['id'] ?? null;
        $this->code = $row['code'];
    }



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}