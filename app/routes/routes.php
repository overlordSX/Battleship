<?php
Router::route(['^/catalog/product/(\d+)$', '^/catalog/product/(\d+)/comments(.+)$'], function (int $productId) {
    ProductController::showProduct($productId);
});

Router::route(['^/catalog/product/(\d+)/comment/new$'], function (int $productId) {
    CommentController::postNewComment($productId);
});

Router::route(['^/catalog/product/new$'], function () {
    ProductController::newProduct();
});

Router::route(['^/$', '^/catalog$', '^/catalog\?.+$'], function () {
    CatalogController::renderPage();
});

Router::route(['^/error-404$'], function () {
    View::generateView('view/errors/404.php');
});

Router::route(['^/error-500$'], function () {
    View::generateView("view/errors/500.php");
});

Router::route(['^/admin/catalog/drop$'], function () {
    CatalogController::dropTable();
});

Router::route(['^/admin/catalog/create$'], function () {
    CatalogController::createTable();
});

Router::route(['^/admin/messages/drop$'], function () {
    CommentController::dropTable();
});

Router::route(['^/admin/messages/create$'], function () {
    CommentController::createTable();
});

Router::route(['^/admin$', '^/admin\?.+$'], function () {
    AdminController::adminPanel();
});

Router::route(['^/catalog/generate/(\d+)$'], function ($quantity) {
    CatalogController::generateProducts($quantity);
});