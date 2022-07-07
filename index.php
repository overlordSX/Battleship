<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список товаров</title>
</head>
<body>

<?php
require_once "routes/Router.php";


//а тут, вызывать контроллер, либо его методы
Router::route('/', function (){
    echo "<h1>Hello, world!</h1>";
});
Router::route('/about', function () {
    echo "<h1>I'm Andrew</h1>";
});

echo $_SERVER['REQUEST_URI'];
Router::execute($_SERVER['REQUEST_URI']);


?>




<?php
/*require_once "database/Database.php";
require_once "database/Products.php";
require_once "database/entity/ProductEntity.php";

Database::getInstance();

Products::createTable();

$product = new ProductEntity("milk", "50%", 500, null);

Products::insertNewProduct($product);

Products::selectAllProducts();

*/?>
</body>
</html>

