<?php

/**
 * Класс отвечающий за взаимодействие Товаров и базы данных.
 */
class Products
{
    //TODO №9 по сути можно сделать приватный конструктор,
    // чтобы нельзя было создать объект, так как все через статические функции работает

    public static function createTable(): void
    {
        //TODO узнать нужно ли это защитить как то от атак
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `products` 
        (`id` INT NOT NULL AUTO_INCREMENT, 
        `name` VARCHAR(200) NOT NULL,
        `description` VARCHAR(500) NOT NULL,
        `price` INT NOT NULL,
        PRIMARY KEY (`id`)) ENGINE = InnoDB;";

        Database::exec($createTableQuery);
    }

    public static function selectAllProducts(): array
    {
        require_once "util/ProductUtil.php";

        $selectAllQuery = "select * from products;";
        $result = Database::queryFetchAll($selectAllQuery);

        //по итогу в резалте список объектов ProductEntity
        $result = ProductUtil::resultToListOfProducts($result);

        //TODO функция по сути должна возвращать список объектов ProductUtil,
        //TODO их далее будет юзать контроллер, чтобы рисовать вью.
        print_r($result);
        return $result;
    }

    public static function insertNewProduct(ProductEntity $product): void
    {
        //TODO №423 не понятно где именно нужно будет делать валидацию
        $insertQuery = "insert into products (name, description, price) values (?, ?, ?)";
        Database::prepareAndExecute(
            $insertQuery,
            [
                $product->getName(),
                $product->getDescription(),
                $product->getPrice()
            ]);
    }
}


