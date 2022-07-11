<?php

/**
 * Класс отвечающий за взаимодействие Товаров и базы данных.
 */
class Products
{

    public static function createTable(): void
    {
        //TODO 1 узнать нужно ли это защитить как то от атак
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `products` 
        (`id` INT NOT NULL AUTO_INCREMENT, 
        `name` VARCHAR(200) NOT NULL,
        `description` VARCHAR(500) NOT NULL,
        `price` INT NOT NULL,
        PRIMARY KEY (`id`)) ENGINE = InnoDB;";

        Database::exec($createTableQuery);
    }

    /**
     * @return ProductEntity[] возвращает массив объктов
     */
    public static function selectAllProducts(): array
    {
        $selectAllQuery = "select * from products;";
        $result = Database::queryFetchAll($selectAllQuery);

        return EntityUtil::resultToListOfEntities("ProductEntity",$result);
    }

    /**
     * @param int $limit сколько записей взять
     * @param int $offset начиная с какой записи
     * @return ProductEntity[] вернет массив объектов
     */
    public static function selectProductsLimitOffset(int $limit, int $offset): array
    {
        $selectLimitOffsetQuery = "select * from products LIMIT $limit OFFSET $offset;";
        $result = Database::queryFetchAll($selectLimitOffsetQuery);

        return EntityUtil::resultToListOfEntities("ProductEntity", $result);

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
            ]);
    }

    public static function dropTable(): void
    {
        $dropTableQuery = "drop table if exists products";
        Database::exec($dropTableQuery);
    }

}


