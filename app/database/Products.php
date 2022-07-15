<?php

/**
 * Класс отвечающий за взаимодействие Товаров и базы данных.
 */
class Products
{

    public static function createTable(): void
    {
        $createTableQuery = "
        CREATE TABLE IF NOT EXISTS `products` 
        (`id` INT NOT NULL AUTO_INCREMENT, 
        `name` VARCHAR(200) NOT NULL,
        `description` VARCHAR(500) NOT NULL,
        `price` INT NOT NULL,
        PRIMARY KEY (`id`)) ENGINE = InnoDB;";

        Database::exec($createTableQuery);
    }

    public static function isTableExists(): bool
    {
        $isExistQuery = '
        SELECT COUNT(*)
        FROM information_schema.tables 
        WHERE table_schema = DATABASE() AND table_name = `products`;';

        return Database::queryFetchRow($isExistQuery);
    }

    public static function getProductsWithQuantityOfActiveCommentsWithSort(int $limit, int $offset = 0, string $sortParam = 'id', string $order = 'asc'): array
    {
        $format = '
        select p.id, name, price, description, count(product_id) as comments
        from products as p LEFT JOIN (
            SELECT * FROM comments
            where activity_status = true
            ) as c
        on (p.id = c.product_id)
        group by p.id, name, price, description
        order by %s %s 
        LIMIT %s OFFSET %s;';

        $query = sprintf($format, $sortParam, $order, $limit, $offset);

        $result = Database::queryFetchAll($query);
        return EntityUtil::resultToListOfEntities("ProductEntity", $result);
    }

    public static function getProductWithId($id): ProductEntity|bool
    {
        $selectAllQuery = "select * from products where id = ?";
        $values = [$id];
        $result = Database::queryFetchRow($selectAllQuery, $values);

        return $result ? EntityUtil::resultToEntity("ProductEntity", $result) : false;
    }

    public static function getCountOfProducts(): int
    {
        $getCountOfProductsQuery = "select COUNT(*) as totalProducts from products";
        return Database::queryFetchRow($getCountOfProductsQuery)['totalProducts'];
    }

    public static function insertNewProduct(ProductEntity $product): void
    {
        $insertQuery = "insert into products (name, description, price) values (?, ?, ?)";
        Database::prepareAndExecute(
            $insertQuery,
            [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice()
            ]
        );
    }

    public static function dropTable(): void
    {
        $dropTableQuery = "drop table if exists products";
        Database::exec($dropTableQuery);
    }

}


