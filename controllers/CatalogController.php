<?php
require_once "database/entity/ProductEntity.php";
require_once "view/View.php";
require_once "database/Products.php";

class CatalogController
{
    public const PRODUCTS_ON_ONE_PAGE = 5;
    public const MAX_INDEXES_WITH_NO_SPACES = 7;//4;


    public static function renderPage($currentPageNumber = 1): void
    {
        //куда то еще нужно прикрутить прерывание буфера
        //ob_start(), ob_get_clean()

        if ($currentPageNumber > 1) {
            $offset = ($currentPageNumber - 1) * self::PRODUCTS_ON_ONE_PAGE;
            $productList = Products::selectProductsLimitOffset(
                self::PRODUCTS_ON_ONE_PAGE,
                $offset);
        } else {
            $productList =  Products::selectProductsLimitOffset(
                self::PRODUCTS_ON_ONE_PAGE
            );
        }

        $totalProducts = Products::getCountOfProducts();
        $view = new View();
        $view->generate('./view/catalog/catalog.php',
            [
                'productList' => $productList,
                'currentPageNumber' => $currentPageNumber,
                'totalProducts' => $totalProducts,
                'PRODUCTS_ON_ONE_PAGE' => self::PRODUCTS_ON_ONE_PAGE,
                'countOfPages' => ceil($totalProducts / self::PRODUCTS_ON_ONE_PAGE),
                'MAX_INDEXES_WITH_NO_SPACES' => self::MAX_INDEXES_WITH_NO_SPACES
            ]);
    }

    public static function dropTable(): void
    {
        Products::dropTable();
    }

    public static function createTable(): void
    {
        Products::createTable();
        CatalogController::renderPage();
    }

    public static function createNewProduct(): void
    {

    }
}