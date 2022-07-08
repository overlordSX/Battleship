<?php

class ProductUtil
{

    /**
     * @param $array
     * @return ProductEntity[]
     */
    public static function resultToListOfProducts($array): array
    {
        $listOfProducts = [];
        foreach ($array as $row) {
            $listOfProducts[] = new ProductEntity(
                $row['name'],
                $row['description'],
                $row['price'],
                $row['id']
            );
        }

        return $listOfProducts;
    }
}