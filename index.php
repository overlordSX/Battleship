<?php
require_once "routes/Router.php";
require_once "controllers/CatalogController.php";
require_once "database/Products.php";
require_once "database/Database.php";


Router::route(['/'], function () {
    echo "<br><a href='/catalog'><button>Каталог</button></a>";
});
Router::route(['/about'], function () {
    echo "<h1>I'm Andrew</h1>";
});

Router::route(['/catalog/page/(\d+)'], function (int $pageNumber) {
    echo "<h1>Page ::: " . $pageNumber . "</h1>";
    CatalogController::renderPage($pageNumber);
});

Router::route(['/templates/error-404.php'], function () {
    require_once "templates/error-404.php";
});

Router::route(['/catalog', '/catalog/'], function () {
    CatalogController::renderPage();
});

Router::route(['/catalog/drop'], function () {
    CatalogController::dropTable();
});

Router::route(['/catalog/create'], function () {
    CatalogController::createTable();
});


echo $_SERVER['REQUEST_URI'];
Router::execute($_SERVER['REQUEST_URI']);
?>

<?php
/*require_once "database/Database.php";
require_once "database/Products.php";
require_once "database/entity/ProductEntity.php";

Database::getInstance();

//Products::createTable();

$product = new ProductEntity("milk", "50%", 500, null);

Products::insertNewProduct($product);

//Products::selectAllProducts();

*/?>
