<?
/**
 * @var int $countOfPages
 * @var int $currentPageNumber
 * @var int $totalProducts
 * @var int $pageUrl
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
                            <p class="card-text">
                                <?
                                $description = $product->getDescription();
                                $description = strlen($description) > 100 ?
                                    mb_substr($product->getDescription(), 0, 95, 'utf-8') . '...' :
                                    $description;
                                echo $description;
                                ?>
                            </p>
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
                'getQuery' => $_GET
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
                foreach ($sortParams as $title => $sortParam): ?>
                    <div class="col">
                        <?
                        $currentSort['sortBy'] = $sortParam;
                        if (
                            isset($_GET['sortBy'])
                            and $_GET['sortBy'] === $currentSort['sortBy']
                            and isset($_GET['order'])
                        ) {
                            $currentSort['order'] = $_GET['order'] === 'desc' ? 'asc' : 'desc';
                        } else {
                            $currentSort['order'] = 'asc';
                        }

                        $sortUrl = 'catalog?' . http_build_query($currentSort);
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
        </div>

    </div>
<?
View::generateView(
    'view/layouts/footer.php'
);
?>