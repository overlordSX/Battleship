<?php

class View
{
    public static function generateView(string $filepath, array $data = []): void
    {
        extract($data);
        include $filepath;
    }
}