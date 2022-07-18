<?php

class ProductController implements ControllerInterface
{
    public function newProduct(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            View::generateView('view/product/new.php');
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $trimPost = [];
            foreach ($_POST as $key => $value) {
                $trimPost[$key] = trim($value);
            }

            $validator = new Validator;
            $validator->make(
                $trimPost,
                [
                    'name' => [new IsRequired(true), new IsString(), new IsLenAllowed(200)],
                    'price' => [new IsRequired(true), new IsPosDigit(), new IsLenAllowed(12)],
                    'description' => [new IsRequired(true), new IsString(), new IsLenAllowed(500)]
                ]
            );

            $fieldsErrors = $validator->isValid() ? [] : $validator->getErrors();

            if (!empty($fieldsErrors)) {
                View::generateView('view/product/new.php', ['trimPost' => $trimPost, 'fieldsErrors' => $fieldsErrors]);
            } else {
                $product = new ProductEntity([
                        'name' => trim($_POST['name']),
                        'description' => trim($_POST['description']),
                        'price' => (int)trim($_POST['price'])
                    ]
                );

                Products::insertNewProduct($product);
                header('Location: /catalog');
            }
        }
    }

    public function showProduct($productId, $fieldsTrimData = null, $fieldsErrors = null): void
    {

        $currentCommentPage = $_GET['page'] ?? 1;

        $product = Products::getProductWithId($productId);
        if (!$product) {
            header('Location: /error-404');
        }

        $totalComments = Comments::getCountOfActiveCommentsWithProductId($productId, true);

        $offset = ($currentCommentPage - 1) * CommentController::COMMENTS_PAGE_SIZE;
        $commentsList = Comments::getAllActiveCommentsWithProductIdLimitOffset(
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
                'trimPost' => $fieldsTrimData ?? [],
                'fieldsErrors' => $fieldsErrors ?? []
            ]
        );
    }

}