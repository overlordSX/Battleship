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
        `activity_status` BOOL NOT NULL default false, 
        `product_id` INT NOT NULL,
        PRIMARY KEY (`id`),
        FOREIGN KEY (`product_id`) 
            REFERENCES `products`(`id`)
            ON DELETE CASCADE
        ) ENGINE = InnoDB;";

        Database::exec($createTableQuery);
    }

    /**
     * @return CommentEntity[] возвращает список объектов
     */
    public static function selectAllComments(): array
    {
        $selectAllQuery = "select * from comments;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("CommentEntity", $result);
    }

    public static function getCountOfComments($productId): int
    {
        $getCountOfProductsQuery = "select COUNT(*) as totalComments from comments where product_id = $productId";
        return Database::queryFetchRow($getCountOfProductsQuery)['totalComments'];
    }

    //todo #1 добавить потом вывод только промодерированных отзывов
    public static function selectAllCommentsWithProductId($productId): array
    {
        $selectAllQuery = "select * from comments where product_id = $productId;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("CommentEntity", $result);
    }

    public static function selectAllCommentsWithProductIdLimitOffset($productId, $limit, $offset): array
    {
        $selectAllQuery = "select * from comments where product_id = $productId limit $limit offset $offset;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("CommentEntity", $result);
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

    public static function dropTable(): void
    {
        $dropTableQuery = "drop table if exists comments";
        Database::exec($dropTableQuery);
    }
}