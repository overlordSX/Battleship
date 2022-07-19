<?php

class CommentController implements ControllerInterface
{
    public const COMMENTS_PAGE_SIZE = 4;

    public function postNewComment($productId): void
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
                (new ProductController)->showProduct($productId, $trimPost, $fieldsErrors);
            } else {
                $ent = new CommentEntity(
                    [
                        'email' => $trimPost['email'],
                        'comment' => $trimPost['comment'],
                        'product_id' => $productId
                    ]
                );

                Comments::insertNewComment($ent);

                header('Location: /catalog/product/' . $productId);
                die();
            }
        }
    }

    public function createTable(): void
    {
        Comments::createTable();
        header('Location: /');
        die();
    }

    public function dropTable(): void
    {
        Comments::dropTable();

        header('Location: /');
        die();
    }
}