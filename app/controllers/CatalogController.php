<?php

class CatalogController implements ControllerInterface
{
    public const PAGE_SIZE = 5;

    public function renderPage(): void
    {
        $currentPageNumber = $_GET['page'] ?? 1;
        $sortParam = $_GET['sortBy'] ?? "id";
        $order = $_GET['order'] ?? 'asc';

        $totalProducts = Products::getCountOfProducts();
        $countOfPages = ceil($totalProducts / self::PAGE_SIZE);

        if ($currentPageNumber > $countOfPages and $countOfPages > 0) {
            header('Location: /error-404');
        }

        $offset = ($currentPageNumber - 1) * self::PAGE_SIZE;

        $productList = Products::getProductsWithQuantityOfActiveCommentsWithSort(
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
                'pageUrl' => 'catalog'
            ]
        );
    }

    public function createTable(): void
    {
        Products::createTable();
        header('Location: /');
        die();
    }

    public function dropTable(): void
    {
        Products::dropTable();
        header('Location: /');
        die();
    }

    public function generateProducts($quantity): void
    {
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