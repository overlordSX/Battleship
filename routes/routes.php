<?php
Router::route(['/'], function () {
    echo "<br><a href='/catalog'><button>Каталог</button></a>";
    echo "<br><a href='/catalog/create'><button>Создать таблицу Товары</button></a>";
    echo "<br><a href='/catalog/drop'><button>Удалить таблицу Товары</button></a>";
    echo "<br><a href='/catalog/generate/5'><button>Генерация 5 товаров</button></a>";
    echo "<br><a href='/catalog/product/new'><button>Добавить Товар</button></a>";

    echo "<br>";
    echo "<br><a href='/messages/create'><button>Создать таблицу Отзывы</button></a>";
    echo "<br><a href='/messages/drop'><button>Удалить таблицу Отзывы</button></a>";
});


Router::route(['/catalog/product/(\d+)', '/catalog/product/(\d+)/comments(.+)'], function (int $productId) {
    ProductController::showProduct($productId, $_SERVER['REQUEST_URI']);
});

Router::route(['/catalog/product/(\d+)/comment/new'], function (int $productId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        CommentController::postNewComment($_POST['email'], $_POST['comment'], $productId);
    }
});


Router::route(['/about'], function () {
    echo "<h1>I'm Andrew</h1>";
});

Router::route(['/catalog/product/new'], function () {
    ProductController::newProduct();
});

Router::route(['/catalog', '/catalog(.+)'], function () {
    CatalogController::renderPage2($_SERVER['REQUEST_URI']);
});

Router::route(['/error-404'], function () {
    require_once "templates/error-404.php";
});

Router::route(['/error-500'], function () {
    require_once "templates/error-500.php";
});

// todo #1 это переделать в экшн кнопки
Router::route(['/catalog/drop'], function () {
    CatalogController::dropTable();
});

Router::route(['/catalog/create'], function () {
    CatalogController::createTable();
});

Router::route(['/messages/drop'], function () {
    CommentController::dropTable();
});

Router::route(['/messages/create'], function () {
    CommentController::createTable();
});

// todo #2 переделать в мини формочку
Router::route(['/catalog/generate/(\d+)'], function ($quantity) {
    CatalogController::generateProducts($quantity);
});