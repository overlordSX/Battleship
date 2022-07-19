<?
Router::route(['^/catalog/product/(\d+)$', '^/catalog/product/(\d+)/comments(.+)$'], ProductController::class, 'showProduct');

Router::route(['^/catalog/product/(\d+)/comment/new$'], CommentController::class, 'postNewComment');

Router::route(['^/catalog/product/new$'], ProductController::class, 'newProduct');

Router::route(['^/$', '^/catalog$', '^/catalog\?.+$'], CatalogController::class, 'renderPage');

Router::route(['^/error-404$'], ErrorController::class , 'get404');

Router::route(['^/error-500$'], ErrorController::class, 'get500');

Router::route(['^/admin/catalog/drop$'], CatalogController::class, 'dropTable');

Router::route(['^/admin/catalog/create$'], CatalogController::class, 'createTable');

Router::route(['^/admin/messages/drop$'], CommentController::class, 'dropTable');

Router::route(['^/admin/messages/create$'], CommentController::class, 'createTable');

Router::route(['^/admin$', '^/admin\?.+$'], AdminController::class, 'adminPanel');

Router::route(['^/catalog/generate/(\d+)$'], CatalogController::class, 'generateProducts');
