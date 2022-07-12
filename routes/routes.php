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


Router::route(['/catalog/product/(\d+)/comments/page/(\d+)'], function (int $productId, int $currentCommentPage) {
    ProductController::showProduct($productId, $currentCommentPage);
});

Router::route(['/catalog/product/(\d+)/comment/new'], function (int $productId) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        CommentController::postNewComment($_POST['email'], $_POST['comment'], $productId);
    }
});

Router::route(['/catalog/product/(\d+)'], function (int $productId) {
    ProductController::showProduct($productId);
});


Router::route(['/about'], function () {
    echo "<h1>I'm Andrew</h1>";
});

Router::route(['/catalog/product/new'], function () {
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        ProductController::getNewProductForm();
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        ProductController::createNewProduct($_POST['name'], $_POST['description'], $_POST['price']);
    }
});

Router::route(['/catalog/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog', $pageNumber);
});

Router::route(['/error-404'], function () {
    require_once "templates/error-404.php";
});

Router::route(['/error-500'], function () {
    require_once "templates/error-500.php";
});



Router::route(['/catalog/sort/price'], function () {
    CatalogController::renderPage('catalog/sort/price', 1, 'price');
});

Router::route(['/catalog/sort/price/desc'], function () {
    CatalogController::renderPage('catalog/sort/price/desc', 1, 'price', 'desc');
});

Router::route(['/catalog/sort/price/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog/sort/price', $pageNumber, 'price');
});

Router::route(['/catalog/sort/price/desc/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog/sort/price/desc', $pageNumber, 'price', 'desc');
});


Router::route(['/catalog/sort/name'], function () {
    CatalogController::renderPage('catalog/sort/name', 1, 'name');
});

Router::route(['/catalog/sort/name/desc'], function () {
    CatalogController::renderPage('catalog/sort/name/desc', 1, 'name', 'desc');
});

Router::route(['/catalog/sort/name/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog/sort/name', $pageNumber, 'name');
});

Router::route(['/catalog/sort/name/desc/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog/sort/name/desc', $pageNumber, 'name', 'desc');
});



Router::route(['/catalog/sort/comments'], function () {
    CatalogController::renderPage('catalog/sort/comments', 1, 'comments');
});

Router::route(['/catalog/sort/comments/desc'], function () {
    CatalogController::renderPage('catalog/sort/comments/desc', 1, 'comments', 'desc');
});

Router::route(['/catalog/sort/comments/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog/sort/comments', $pageNumber, 'comments');
});

Router::route(['/catalog/sort/comments/desc/page/(\d+)'], function (int $pageNumber) {
    CatalogController::renderPage('catalog/sort/comments/desc', $pageNumber, 'comments', 'desc');
});

Router::route(['/catalog', '/catalog/'], function () {
    CatalogController::renderPage('catalog');
});

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


Router::route(['/catalog/generate/(\d+)'], function ($quantity) {
    CatalogController::generateProducts($quantity);
});