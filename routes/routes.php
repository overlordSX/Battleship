<?php
Router::route(['/'], function () {
    echo "<br><a href='/catalog'><button>Каталог</button></a>";
    echo "<br><a href='/catalog/create'><button>Создать таблицу Товары</button></a>";
    echo "<br><a href='/catalog/drop'><button>Удалить таблицу Товары</button></a>";
    echo "<br><a href='/catalog/generate/5'><button>Генерация 5 продуктов</button></a>";
});
Router::route(['/about'], function () {
    echo "<h1>I'm Andrew</h1>";
});

Router::route(['/catalog/page/(\d+)'], function (int $pageNumber) {
    echo "<h1>Page ::: " . $pageNumber . "</h1>";
    CatalogController::renderPage($pageNumber);
});

Router::route(['/error-404'], function () {
    require_once "templates/error-404.php";
});

Router::route(['/error-500'], function () {
    require_once "templates/error-500.php";
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

Router::route(['/catalog/generate/(\d+)'], function ($quantity) {
    CatalogController::generateProducts($quantity);
});