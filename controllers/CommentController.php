<?php

class CommentController
{
    public const COMMENTS_PAGE_SIZE = 4;

    public static function postNewComment($email, $comment, $productId): void
    {
        $product = Products::getProductWithId($productId);
        if (!$product) {
            header('Location: /error-404');
        }

        //todo 2 проверка валидности данных
        $ent = new CommentEntity(['email' => $email, 'comment' => $comment, 'product_id' => $productId]);
        //todo 1 еще нужна проверка на существование такого продукта
        Comments::insertNewComment($ent);
        header('Location: /catalog/product/' . $productId);
        die();
    }

    public static function createTable(): void
    {
        try {
            Comments::createTable();
            header('Location: /');
        } catch (Exception $exception) {
            //todo #1 добавить проверку на создание файла, и директории под него НУЖНО ЛИ?
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }
    }

    public static function dropTable(): void
    {
        try {
            Comments::dropTable();

            header('Location: /');
            die();
        } catch (Exception $exception) {
            //todo #2 добавить проверку на создание файла, и директории под него НУЖНО ЛИ?
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }
    }
}