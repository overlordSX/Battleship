<?php

class Products
{

    public function createTable(): void
    {
        $createTableQuery = "CREATE TABLE IF NOT EXISTS `products` 
        (`id` INT NOT NULL AUTO_INCREMENT, 
        `name` VARCHAR(200) NOT NULL,
        `description` VARCHAR(500) NOT NULL,
        `price` INT NOT NULL,
        PRIMARY KEY (`id`)) ENGINE = InnoDB;";

        Database::exec($createTableQuery);
    }

    public function selectAll(): void
    {
        $selectAllQuery = "select * from products;";
        $result = Database::queryFetchAll($selectAllQuery);
        print_r($result);
    }


#$stmt3 = $pdo->exec("drop table if exists products");

#$stmt3 = $pdo->exec("insert into products (name, description, price) values ('djldskf', 'dkfjlds;fkjs;', 50)");

}


