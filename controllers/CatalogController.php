<?php

class CatalogController
{
    public const PAGE_SIZE = 5;

    public static function renderPage($pageUrl, $currentPageNumber = 1, $sortParam = "", $order = 'asc'): void
    {
        //todo №1 что делать с тем когда таблицы нет
        Products::createTable();
        Comments::createTable();
        $productList = [];
        $totalProducts = Products::getCountOfProducts();
        $offset = ($currentPageNumber - 1) * self::PAGE_SIZE;
        if ($sortParam !== "") {
            if ($sortParam === 'name') {
                $productList = Products::selectProductsWithQuantityOfCommentsSortByNameLimitOffset(
                    self::PAGE_SIZE,
                    $offset,
                    $order
                );
            } elseif ($sortParam === 'price') {
                $productList = Products::selectProductsWithQuantityOfCommentsSortByPriceLimitOffset(
                    self::PAGE_SIZE,
                    $offset,
                    $order
                );
            } elseif ($sortParam === 'comments') {
                echo " comments ";
                $productList = Products::selectProductsWithQuantityOfCommentsSortByCommentsLimitOffset(
                    self::PAGE_SIZE,
                    $offset,
                    $order
                );
            }
        } else {
            $productList = Products::selectProductsWithQuantityOfCommentsLimitOffset(
                self::PAGE_SIZE,
                $offset
            );
        }


        $view = new View();
        $view->generateView('./view/catalog/catalog.php',
            [
                'productList' => $productList,
                'currentPageNumber' => $currentPageNumber,
                'totalProducts' => $totalProducts,
                'countOfPages' => ceil($totalProducts / self::PAGE_SIZE),
                'pageUrl' => $pageUrl
            ]
        );
    }

    public static function dropTable(): void
    {
        Products::dropTable();

        header('Location: /');
        die();
    }

    public static function createTable(): void
    {
        Products::createTable();
        header('Location: /');
        die();

    }

    public static function generateProducts($quantity): void
    {
        Products::createTable();

        for ($i = 1; $i <= $quantity; $i++) {
            $product = new ProductEntity(
                [
                    'name' => "Товар " . rand(0, 50000),
                    'description' => "Описание " . $i,
                    'price' => rand(0, 50000),
                    'id' => null
                ]
            );

            Products::insertNewProduct($product);
        }
        header('Location: /');
        die();

    }
}