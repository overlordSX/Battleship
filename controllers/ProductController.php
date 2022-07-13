<?php

class ProductController
{
    public static function newProduct(): void
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') {
            View::generateView('view/product/new.php');
        }
        // todo #1 это нормально?
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){



            //if (Validator::make($data))
            //если ок добавить, иначе сообщить об ошибках


            $product = new ProductEntity([
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'price' => $_POST['price']
                ]
            );
            Products::insertNewProduct($product);

            header('Location: /catalog/');
        }
    }

    public static function showProduct($productId, $pageUrl): void
    {

        $parse = parse_url($pageUrl);
        $start = $parse['query'] ?? '';
        parse_str($start, $query);

        $currentCommentPage = $query['page'] ?? 1;

        $product = Products::getProductWithId($productId);
        if (!$product) {
            header('Location: /error-404');
        }

        $totalComments = Comments::getCountOfComments($productId);

        $offset = ($currentCommentPage - 1) * CommentController::COMMENTS_PAGE_SIZE;
        $commentsList = Comments::selectAllCommentsWithProductIdLimitOffset(
            productId: $productId,
            limit: CommentController::COMMENTS_PAGE_SIZE,
            offset: $offset
        );

        View::generateView(
            "view/product/exist.php",
            [
                'product' => $product,
                'commentsList' => $commentsList,
                'commentPage' => $currentCommentPage,
                'totalPages' => ceil($totalComments / CommentController::COMMENTS_PAGE_SIZE),
                'query' => $query
            ]
        );
    }

}