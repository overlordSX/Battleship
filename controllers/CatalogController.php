<?php

class CatalogController
{
    public const PAGE_SIZE = 5;

    public static function renderPage($currentPageNumber = 1): void
    {
        Products::createTable();
        $totalProducts = Products::getCountOfProducts();
        $offset = ($currentPageNumber - 1) * self::PAGE_SIZE;
        $productList = Products::selectProductsLimitOffset(
            self::PAGE_SIZE,
            $offset
        );

        $view = new View();
        $view->generateView('./view/catalog/catalog.php',
            [
                'productList' => $productList,
                'currentPageNumber' => $currentPageNumber,
                'totalProducts' => $totalProducts,
                'countOfPages' => ceil($totalProducts / self::PAGE_SIZE)
            ]
        );
    }

    public static function dropTable(): void
    {
        Products::dropTable();
    }

    public static function createTable(): void
    {
        Products::createTable();
        self::renderPage();
    }

    public static function createNewProduct(): void
    {
    }

    public static function generateProducts($quantity): void
    {
        Products::createTable();

        for ($i = 1; $i <= $quantity; $i++) {
            $product = new ProductEntity(
                [
                    'name' => "milk " . $i,
                    'description' => $i . " %",
                    'price' => $i,
                    'id' => null
                ]
            );

            Products::insertNewProduct($product);
        }

    }
}