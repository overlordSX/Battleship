<?php

class ProductController
{
    public static function getNewProductForm(): void
    {
        $createFormView = new View();
        $createFormView->generateView('view/product/new.php');
    }

    public static function createNewProduct($name, $description, $price): void
    {
        Products::createTable();
        $product = new ProductEntity([
                'name' => $name,
                'description' => $description,
                'price' => $price
            ]
        );
        Products::insertNewProduct($product);

        header('Location: /catalog/');
    }

    public static function showProduct($productId, $currentCommentPage = 1): void
    {
        Comments::createTable();

        $product = Products::selectProductWithId($productId)[0];

        // а не нужно ли отсюда вызывать мессэдж контроллер
        $totalComments = Comments::getCountOfComments($productId);

        $offset = ($currentCommentPage - 1) * CommentController::COMMENTS_PAGE_SIZE;
        $commentsList = Comments::selectAllCommentsWithProductIdLimitOffset(
            productId: $productId,
            limit: CommentController::COMMENTS_PAGE_SIZE,
            offset: $offset
        );

        $showProductView = new View();
        $showProductView->generateView(
            "view/product/exist.php",
            [
                'product' => $product,
                'commentsList' => $commentsList,
                'commentPage' => $currentCommentPage,
                'totalPages' => ceil($totalComments / CommentController::COMMENTS_PAGE_SIZE)
            ]
        );
    }

}