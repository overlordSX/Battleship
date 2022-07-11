<?php

/**
 * @var int $countOfPages Количество страниц товаров
 * @var int $currentPageNumber Номер текущей страницы
 */

$paginationArray['1'] = "catalog/page/1";
if ($countOfPages > 1) {
    $paginationArray['2'] = "catalog/page/2";
    for ($pageNumber = 1; $pageNumber < $countOfPages; $pageNumber++) {
        if (abs($currentPageNumber - $pageNumber) < 2) {
            $paginationArray[$pageNumber . ""] = "catalog/page/" . $pageNumber;
        }
    }
    $paginationArray[$countOfPages - 1 . ""] = "catalog/page/" . $countOfPages - 1;
    $paginationArray[$countOfPages . ""] = "catalog/page/" . $countOfPages;

    ksort($paginationArray);
}

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