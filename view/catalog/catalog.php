<?php
/**
 * @var int $countOfPages
 * @var int $currentPageNumber
 * @var int $totalProducts
 * @var int $pageUrl
 */
?>

<?php
$headerView = new View();
$headerView->generateView(
    'view/layouts/header.php',
    ['title' => "Каталог товаров, стр. " . $currentPageNumber]
);
?>

    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
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
                            <p class="card-text text-muted text-end">Количество
                                отзывов: <span class="badge
                                <?php if ($product->getQuantityOfComments() > 0) {
                                    echo "bg-primary";
                                } else {
                                    echo "bg-secondary";
                                } ?>
                                rounded-pill"><?= $product->getQuantityOfComments() ?></span></p>
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


        <?php
        $paginationView = new View();
        $paginationView->generateView(
            "view/pagination/pagination.php",
            [
                'countOfPages' => $countOfPages,
                'currentPageNumber' => $currentPageNumber,
                'currentUrl' => $pageUrl
            ]
        );

        ?>

        <br>
        <div class="container">
            <div class="row row-cols-auto">
                <div class="col">
                    <h4>Сортировка</h4>
                </div>

                <div class="col">
                    <?php
                        if ($_SERVER['REQUEST_URI'] === '/catalog/sort/price') { ?>
                        <a href="/catalog/sort/price/desc">
                        <?php
                        } else { ?>
                        <a href="/catalog/sort/price">
                            <?php
                            }
                    ?>
                            <button class="btn btn-outline-secondary">По цене</button>
                        </a>
                </div>

                <div class="col">
                    <?php
                    if ($_SERVER['REQUEST_URI'] === '/catalog/sort/name') { ?>
                    <a href="/catalog/sort/name/desc">
                        <?php
                        } else { ?>
                        <a href="/catalog/sort/name">
                            <?php
                            }
                            ?>
                        <button class="btn btn-outline-secondary">По названию</button>
                    </a>
                </div>

                <div class="col">
                    <?php
                    if ($_SERVER['REQUEST_URI'] === '/catalog/sort/comments') { ?>
                    <a href="/catalog/sort/comments/desc">
                        <?php
                        } else { ?>
                        <a href="/catalog/sort/comments">
                            <?php
                            }
                            ?>
                        <button class="btn btn-outline-secondary">По комментариям</button>
                    </a>
                </div>

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

<?php
$footerView = new View();
$footerView->generateView(
    'view/layouts/footer.php'
);
?>