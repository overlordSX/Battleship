<?php

$pdo = require 'connection.php';

$createTableQuery = "CREATE TABLE IF NOT EXISTS `products` ( `id` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(200) NOT NULL ,
 `description` VARCHAR(500) NOT NULL , `price` INT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;";


$stmt = $pdo->exec($createTableQuery);
#echo $stmt;

#$stmt3 = $pdo->exec("drop table if exists products");

#$stmt3 = $pdo->exec("insert into products (name, description, price) values ('djldskf', 'dkfjlds;fkjs;', 50)");

$stmt2 = $pdo->query("select * from products;");
while ($row = $stmt2->fetch())
{

    foreach ($row as $key => $value)
    {
        echo " {$key} => {$value} <br>";
    }

    echo "<br>";
    print_r($row);
    echo "<br><br>";
}


