<?php
/**
 * @var int $countOfPages
 * @var int $currentPageNumber
 * @var int $totalProducts
 */
?>

<?php
$headerView = new View();
$headerView->generateView(
    'view/layouts/header.php',
    ['title' => "Каталог товаров, стр. " . $currentPageNumber]
);
?>

    <table>
        <tr>
            <td>Id</td>
            <td>Название</td>
            <td>Описание</td>
            <td>Цена</td>
        </tr>
        <?php
        /**
         * @var ProductEntity[] $productList
         * @var ProductEntity $product
         */
        foreach ($productList as $product): ?>
            <tr>
                <td><?= $product->getId() ?></td>
                <td><?= $product->getName() ?></td>
                <td><?= $product->getDescription() ?></td>
                <td><?= $product->getPrice() ?></td>
            </tr>
        <? endforeach; ?>
    </table>

<?php
$paginationView = new View();
$paginationView->generateView(
    "view/pagination/pagination.php",
    [
        'countOfPages' => $countOfPages,
        'currentPageNumber' => $currentPageNumber
    ]
);

?>

    <br>
    <a href="/">
        <button>На главную</button>
    </a>

    <h2>всего страниц будет ::: <?= $countOfPages ?></h2>
    <h2>всего продуктов в БД ::: <?= $totalProducts ?></h2>
    <h2>сколько отображается на одной старице ::: <?= CatalogController::PAGE_SIZE ?></h2>

    <br>

<?php
$footerView = new View();
$footerView->generateView(
    'view/layouts/footer.php'
);
?>