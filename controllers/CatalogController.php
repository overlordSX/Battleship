<?php

class CatalogController
{
    public const PAGE_SIZE = 5;

    public static function renderPage2($pageUrl): void
    {
        $parse = parse_url($pageUrl);
        $start = $parse['query'] ?? '';
        parse_str($start, $query);

        $currentPageNumber = $query['page'] ?? 1;
        $sortParam = $query['sortBy'] ?? "id";
        $order = $query['order'] ?? 'asc';

        try {
            //todo #1 сделать 500 ошибку когда таблицы не созданы
            $totalProducts = Products::getCountOfProducts();
            $countOfPages = ceil($totalProducts / self::PAGE_SIZE);

            if ($currentPageNumber > $countOfPages and $countOfPages > 0) {
                header('Location: /error-404');
            }

            $offset = ($currentPageNumber - 1) * self::PAGE_SIZE;

            $productList = Products::getProductsWithQuantityOfCommentsWithSort(
                self::PAGE_SIZE,
                $offset,
                $sortParam,
                $order
            );

            View::generateView('./view/catalog/catalog.php',
                [
                    'productList' => $productList,
                    'currentPageNumber' => $currentPageNumber,
                    'totalProducts' => $totalProducts,
                    'countOfPages' => $countOfPages,
                    'pageUrl' => 'catalog',
                    'query' => $query
                ]
            );

        } catch (Exception $exception) {
            //todo #1 добавить проверку на создание файла, и директории под него НУЖНО ЛИ?
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }

    }

    public static function createTable(): void
    {
        try {
            Products::createTable();
            header('Location: /');
        } catch (Exception $exception) {
            //todo #1 добавить проверку на создание файла, и директории под него НУЖНО ЛИ?
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }
    }

    public static function dropTable(): void
    {
        try {
            Products::dropTable();

            header('Location: /');
            die();
        } catch (Exception $exception) {
            //todo #2 добавить проверку на создание файла, и директории под него НУЖНО ЛИ?
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }
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