<?php

class View
{
    public function generateView(string $filepath, array $data = []): void
    {
        extract($data);
        include $filepath;
    }
}