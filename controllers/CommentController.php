<?php

class CommentController
{
    public const COMMENTS_PAGE_SIZE = 4;

    public static function postNewComment($email, $comment, $productId): void
    {
        //todo 2 проверка валидности данных
        $ent = new CommentEntity(['email' => $email, 'comment' => $comment, 'product_id' => $productId]);
        //todo 1 еще нужна проверка на существование такого продукта
        Comments::insertNewComment($ent);
        header('Location: /catalog/product/' . $productId);
        die();
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