<?php

use Battleship\App\Routing\Router;

header('Access-Control-Allow-Origin: *');

require_once "Battleship/init.php";


Router::execute($_SERVER['REQUEST_URI']);