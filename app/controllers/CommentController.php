<?php

class CommentController
{
    public const COMMENTS_PAGE_SIZE = 4;

    public static function postNewComment($productId): void
    {
        $product = Products::getProductWithId($productId);
        if (!$product) {
            header('Location: /error-404');
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
                    'email' => [new IsRequired(true), new IsString(), new IsEmail(), new IsLenAllowed(200)],
                    'comment' => [new IsRequired(true), new IsString()]
                ]
            );

            $fieldsErrors = $validator->isValid() ? [] : $validator->getErrors();


            if (!empty($fieldsErrors)) {
                ProductController::showProduct($productId, $trimPost, $fieldsErrors);
            } else {
                $ent = new CommentEntity(
                    [
                        'email' => $trimPost['email'],
                        'comment' => $trimPost['comment'],
                        'product_id' => $trimPost['product_id']
                    ]
                );

                Comments::insertNewComment($ent);

                header('Location: /catalog/product/' . $productId);
                die();
            }
        }
    }

    public static function createTable(): void
    {
        Comments::createTable();
        header('Location: /');
        die();
    }

    public static function dropTable(): void
    {
        Comments::dropTable();

        header('Location: /');
        die();
    }
}