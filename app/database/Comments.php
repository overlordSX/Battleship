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

    public static function moderateComment($commentId ): void
    {
        $query = '
        UPDATE comments
        SET
            activity_status = true
        WHERE
            id = ?';

        Database::prepareAndExecute($query, [$commentId]);
    }

    public static function getCountOfActiveCommentsWithProductId($productId): int
    {
        $getCountOfProductsQuery = "select COUNT(*) as totalComments from comments where product_id = ? and activity_status = true";
        return Database::queryFetchRow($getCountOfProductsQuery, [$productId])['totalComments'];
    }

    public static function getCountOfAllNotActiveComments(): int
    {
        $getCountOfProductsQuery = "select COUNT(*) as totalComments from comments where activity_status = false";
        return Database::queryFetchRow($getCountOfProductsQuery)['totalComments'];
    }



    public static function getAllActiveCommentsWithProductIdLimitOffset($productId, $limit, $offset): array
    {
        $selectAllQuery = "select * from comments where product_id = $productId and activity_status = true limit $limit offset $offset;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("CommentEntity", $result);
    }

    public static function getAllCommentsWithLimitOffsetNonModerate(int $limit, int $offset = 0): array
    {
        $selectAllQuery = "select * from comments where activity_status = 0 limit $limit offset $offset;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("CommentEntity", $result);
    }

    public static function insertNewComment(CommentEntity $comment): void
    {
        $insertQuery = "insert into comments (email, comment, product_id, activity_status) values (?, ?, ?, 0)";
        Database::prepareAndExecute(
            $insertQuery,
            [
                $comment->getEmail(),
                $comment->getComment(),
                $comment->getProductId()
            ]
        );
    }

    public static function dropTable(): void
    {
        $dropTableQuery = "drop table if exists comments";
        Database::exec($dropTableQuery);
    }
}