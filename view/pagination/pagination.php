<?php

/**
 * @var int $countOfPages Количество страниц товаров
 * @var int $currentPageNumber Номер текущей страницы
 * @var string $currentUrl Ссылка текущей страницы
 */

$paginationArray['1'] = $currentUrl . "/page/1";
if ($countOfPages > 1) {
    $paginationArray['2'] = $currentUrl . "/page/2";
    for ($pageNumber = 1; $pageNumber < $countOfPages; $pageNumber++) {
        if (abs($currentPageNumber - $pageNumber) < 2) {
            $paginationArray[$pageNumber . ""] = $currentUrl . "/page/" . $pageNumber;
        }
    }
    $paginationArray[$countOfPages - 1 . ""] = $currentUrl . "/page/" . $countOfPages - 1;
    $paginationArray[$countOfPages . ""] = $currentUrl . "/page/" . $countOfPages;

    ksort($paginationArray);
}

?>

<div class="col">
    <div class="btn-toolbar" role="toolbar">
        <div class="btn-group me-2" role="group">
            <?php

            $prevPage = '1';
            foreach ($paginationArray

            as $pageNumber => $link) {
            if ($pageNumber - $prevPage > 1) { ?>
        </div>
        <div class="btn-group me-2" role="group">
            <?php
            }
            $prevPage = $pageNumber;

            if ($pageNumber == $currentPageNumber) {
                ?>
                <button type="button" class="btn btn-secondary" disabled><?= $currentPageNumber ?></button>
                <?php
            } else { ?>
                <a href="/<?= $link ?>" class="btn btn-outline-primary" role="button"><?= $pageNumber ?></a>
                <?php
            }
            } ?>
        </div>
    </div>
</div>
