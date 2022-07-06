<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список товаров</title>
</head>
<body>


<?php
require_once "database/Database.php";

Database::getInstance();

require_once "database/Products.php";
$product = new Products();

$product->createTable();
$product->selectAll();

?>

</body>
</html>

