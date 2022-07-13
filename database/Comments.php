<?php

/**
 * Класс отвечающий за взаимодействие Отзывов и базы данных
 */
class Comments
{
    public static function createTable(): void
    {
        $createTableQuery = "
        CREATE TABLE IF NOT EXISTS `comments` 
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

    public static function isTableExist(): bool
    {
        $isExistQuery = '
        SELECT *
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() AND table_name = `comments`;';

        var_dump(Database::queryFetchRow($isExistQuery));
        return Database::queryFetchRow($isExistQuery);
    }

    public static function getCountOfComments($productId): int
    {
        $getCountOfProductsQuery = "select COUNT(*) as totalComments from comments where product_id = $productId";
        return Database::queryFetchRow($getCountOfProductsQuery)['totalComments'];
    }

    //TODO #1 добавить вывод промодерированных отзывов
    public static function selectAllCommentsWithProductIdLimitOffset($productId, $limit, $offset): array
    {
        $selectAllQuery = "select * from comments where product_id = $productId limit $limit offset $offset;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("CommentEntity", $result);
    }

    public static function insertNewComment(CommentEntity $comment): void
    {
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