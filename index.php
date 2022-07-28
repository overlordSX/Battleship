<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

use Battleship\App\Routing\Router;

require_once "Battleship/init.php";


Router::execute($_SERVER['REQUEST_URI']);