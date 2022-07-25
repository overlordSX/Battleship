<?php

namespace Battleship\App\Controllers\Util;


class JsonUtil
{
    public static function makeAnswer(array $data, int $depth = 512): void
    {
        header('Content-Type: application/json');
        echo json_encode($data, $depth);
        die();
    }

}