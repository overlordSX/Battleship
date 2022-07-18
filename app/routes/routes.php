<?php
Router::route(['^/catalog/product/(\d+)$', '^/catalog/product/(\d+)/comments(.+)$'], ProductController::class, 'showProduct');
/*function (int $productId) {
    ProductController::showProduct($productId);
});*/

Router::route(['^/catalog/product/(\d+)/comment/new$'], CommentController::class, 'postNewComment');


Router::route(['^/catalog/product/new$'], ProductController::class, 'newProduct');


Router::route(['^/$', '^/catalog$', '^/catalog\?.+$'], CatalogController::class, 'renderPage');


Router::route(
    ['^/error-404$'],
    ErrorController::class , 'get404'
);

Router::route(
    ['^/error-500$'],
    ErrorController::class, 'get500'
);

Router::route(['^/admin/catalog/drop$'], CatalogController::class, 'dropTable');
/*    function () {
    CatalogController::dropTable();
});*/

Router::route(['^/admin/catalog/create$'], CatalogController::class, 'createTable');
/*function () {
    CatalogController::createTable();
});*/

Router::route(['^/admin/messages/drop$'], CommentController::class, 'dropTable');
/*function () {
    CommentController::dropTable();
});*/

Router::route(['^/admin/messages/create$'], CommentController::class, 'createTable');
/*function () {
    CommentController::createTable();
});*/

Router::route(['^/admin$', '^/admin\?.+$'], AdminController::class, 'adminPanel');
/*function () {
    AdminController::adminPanel();
});*/

Router::route(['^/catalog/generate/(\d+)$'], CatalogController::class, 'generateProducts');
/*    function ($quantity) {
    CatalogController::generateProducts($quantity);
});*/