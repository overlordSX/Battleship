<?
/**
 * @var int $countOfPages
 * @var int $currentPageNumber
 * @var int $totalProducts
 * @var int $pageUrl
 * @var array $query
 */
?>

<?
View::generateView(
    'view/layouts/header.php',
    ['title' => "Каталог товаров, стр. " . $currentPageNumber]
);
?>

    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?
            /**
             * @var ProductEntity[] $productList
             * @var ProductEntity $product
             */
            foreach ($productList as $product): ?>
                <div class="col pt-3">
                    <div class="card" style="width: 18rem;">
                        <div class="card-body">
                            <h5 class="card-title"><?= $product->getName() ?></h5>
                            <h6 class="card-subtitle mb-2 text-muted"><?= $product->getPrice() . " ₽" ?></h6>
                            <p class="card-text"><?= $product->getDescription() ?></p>
                            <?
                            $commentBadgeStyle = "badge rounded-pill ";
                            $commentBadgeStyle .= ($product->getQuantityOfComments() > 0) ? "bg-primary" : "bg-secondary";
                            ?>
                            <p class="card-text text-muted text-end">Количество отзывов:
                                <span class="<?= $commentBadgeStyle ?>">
                                    <?= $product->getQuantityOfComments() ?>
                                </span>
                            </p>
                            <div class="row row-cols-2 align-items-center justify-content-center">
                                <a href="<?= "/catalog/product/" . $product->getId() ?>"
                                   class="btn btn-primary">Просмотр</a>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
            <div class="col pt-3">
                <div class="card" style="width: 18rem;">
                    <div class="card-body">
                        <div class="row row-cols-2 align-items-center justify-content-center">
                            <div class="col">
                                <a href="/catalog/product/new"
                                   class="btn btn-outline-primary ">Добавить товар</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <?
        View::generateView(
            "view/pagination/pagination.php",
            [
                'countOfPages' => $countOfPages,
                'currentPageNumber' => $currentPageNumber,
                'currentUrl' => $pageUrl,
                'query' => $query
            ]
        );
        ?>

        <br>
        <div class="container">
            <div class="row row-cols-auto">
                <div class="col">
                    <h4>Сортировка</h4>
                </div>


                <?
                $sortParams =
                    [
                        'По цене' => 'price',
                        'По названию' => 'name',
                        'По отзывам' => 'comments'
                    ];
                $selfQuery = [];
                foreach ($sortParams as $title => $sortParam): ?>
                    <div class="col">
                        <?
                        $copyQuery['sortBy'] = $sortParam;
                        if (isset($query['sortBy'])) {
                            if ($query['sortBy'] == $copyQuery['sortBy']) {
                                $copyQuery['order'] = isset($query['order']) ? null : 'desc';
                            }
                        }

                        $sortUrl = 'catalog?' . http_build_query($copyQuery);

                        ?>
                        <a href="<?= $sortUrl ?>">
                            <button class="btn btn-outline-secondary"><?= $title ?></button>
                        </a>
                    </div>
                <? endforeach; ?>

                <div class="col">
                    <a href="/catalog">
                        <button class="btn btn-outline-secondary">Сброс</button>
                    </a>
                </div>
            </div>

            <div class="pt-3 row row-cols-auto align-items-center justify-content-center">
                <div class="col">
                    <a href="/">
                        <button class="btn btn-outline-secondary">На главную</button>
                    </a>
                </div>
            </div>
        </div>


        <h2>всего страниц будет ::: <?= $countOfPages ?></h2>
        <h2>всего продуктов в БД ::: <?= $totalProducts ?></h2>
        <h2>сколько отображается на одной старице ::: <?= CatalogController::PAGE_SIZE ?></h2>

        <br>
    </div>

<?
View::generateView(
    'view/layouts/footer.php'
);
?>