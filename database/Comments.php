<?php

/**
 * Класс отвечающий за взаимодействие Отзывов и базы данных
 */
class Comments
{
    public static function createTable(): void
    {
        //TODO узнать нужно ли это защитить как то от атак
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `comments` 
        (`id` INT NOT NULL AUTO_INCREMENT, 
        `email` VARCHAR(200) NOT NULL,
        `comment` VARCHAR(500) NOT NULL,
        `product_id` INT NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`product_id`) 
            REFERENCES `products`(`id`)
            ON DELETE CASCADE
        ) ENGINE = InnoDB;";

        Database::exec($createTableQuery);
    }

    /**
     * @return ProductEntity[] возвращает список объектов
     */
    public static function selectAllComments(): array
    {
        require_once "util/ProductUtil.php";

        $selectAllQuery = "select * from comments;";
        $result = Database::queryFetchAll($selectAllQuery);

        return CommentUtil::resultToListOfProducts($result);
    }

    public static function insertNewComment(CommentEntity $comment): void
    {
        //TODO №5 не понятно где именно нужно будет делать валидацию
        //TODO #6 так же тут где то нужно будет делать проверку на то что такой продукт существует
        $insertQuery = "insert into comments (email, comment, product_id) values (?, ?, ?)";
        Database::prepareAndExecute(
            $insertQuery,
            [
                $comment->getEmail(),
                $comment->getComment(),
                $comment->getProductId()
            ]);
    }
}