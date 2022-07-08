<?php

class View
{
    public function generate(string $filepath, array $data = []): void
    {
        extract($data);
        include $filepath;
    }

    public static function getHeader(): void
    {
        //include
    }
}