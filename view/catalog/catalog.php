<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<p>include header</p>
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

$paginationArray = [];
$paginationArray['1'] = "catalog/page/1";
$paginationArray['2'] = "catalog/page/2";
for ($pageNumber = 1; $pageNumber < $countOfPages; $pageNumber++) {
    if (abs($currentPageNumber - $pageNumber) < 2) {
        $paginationArray[$pageNumber . ""] = "catalog/page/" . $pageNumber;
    }
}
$paginationArray[$countOfPages - 1 . ""] = "catalog/page/" . $countOfPages - 1;
$paginationArray[$countOfPages . ""] = "catalog/page/" . $countOfPages;

ksort($paginationArray);

$prevPage = '1';
foreach ($paginationArray as $pageNumber => $link) {
    if ($pageNumber - $prevPage > 1) {
        echo "<b>..</b>";
    }
    $prevPage = $pageNumber;

    if ($pageNumber == $currentPageNumber) {
        ?>
        <b><?= $currentPageNumber ?></b>
        <?php
    } else { ?>
        <a href="/<?= $link ?>"><?= $pageNumber ?></a>
        <?php
    }
}

echo "<br><br>";

?>

<a href="/">
    <button>На главную</button>
</a>

<h2>всего страниц будет ::: <?= $countOfPages ?></h2>
<h2>всего продуктов в БД ::: <?= $totalProducts ?></h2>
<h2>сколько отображается на одной старице ::: <?= $PRODUCTS_ON_ONE_PAGE ?></h2>

<br>

//footer
</body>
</html>
