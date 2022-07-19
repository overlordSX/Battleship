<?php

class JsonUtil
{
    public static function makeAnswer(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }

}