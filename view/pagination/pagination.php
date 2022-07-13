<?php

/**
 * @var int $countOfPages Количество страниц товаров
 * @var int $currentPageNumber Номер текущей страницы
 * @var string $currentUrl Ссылка текущей страницы
 * @var array $query
 */


$query['page'] = 1;
$paginationArray['1'] = $currentUrl . '?' . http_build_query($query);
if ($countOfPages > 1) {
    $query['page'] = 2;
    $paginationArray['2'] = $currentUrl . '?' . http_build_query($query);
    for ($pageNumber = 1; $pageNumber < $countOfPages; $pageNumber++) {
        if (abs($currentPageNumber - $pageNumber) < 2) {
            $query['page'] = $pageNumber;
            $paginationArray[$pageNumber . ""] = $currentUrl . '?' . http_build_query($query);
        }
    }
    $query['page'] = $countOfPages - 1;
    $paginationArray[$countOfPages - 1 . ""] = $currentUrl . '?' . http_build_query($query);
    $query['page'] = $countOfPages;
    $paginationArray[$countOfPages . ""] = $currentUrl . '?' . http_build_query($query);

    ksort($paginationArray);
}
?>

<div class="col">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group me-2" role="group">
            <?
            $prevPage = '1';
            foreach ($paginationArray as $pageNumber => $link) {
            if ($pageNumber - $prevPage > 1) { ?>
        </div>
        <div class="btn-group me-2" role="group">
            <?
            }
            $prevPage = $pageNumber;

            if ($pageNumber == $currentPageNumber) {
                ?>
                <button type="button" class="btn btn-secondary" disabled><?= $currentPageNumber ?></button>
                <?
            } else { ?>
                <a href="/<?= $link?>" class="btn btn-outline-primary" role="button"><?= $pageNumber ?></a>
                <?
            }
            } ?>
        </div>
    </div>
</div>
