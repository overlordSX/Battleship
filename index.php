<?php
require_once "init.php";

echo $_SERVER['REQUEST_URI'];
Router::execute($_SERVER['REQUEST_URI']);