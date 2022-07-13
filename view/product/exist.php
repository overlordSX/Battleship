<?
/**
 * @var ProductEntity $product
 * @var CommentEntity[] $commentsList
 * @var int $commentPage
 * @var int $totalPages
 * @var array $query
 */

View::generateView(
    'view/layouts/header.php',
    ['title' => "Товар №" . $product->getId()]
);
?>

    <div class="container">
    <div class="row">
        <div class="col p-2">
            <div class="card">
                <div class="card-body">
                    <div class="p-3">
                        <h5 class="card-title"><?= $product->getName() ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $product->getPrice() ?></h6>
                        <p class="card-text"><?= $product->getDescription() ?></p>
                    </div>
                    <hr>

                    <div class="row row-cols-auto justify-content-center">
                        <div class="col">
                            <a href="/catalog">
                                <button type="button" class="btn btn-outline-primary">Каталог</button>
                            </a>
                        </div>
                        <div class="col">
                            <a href="/">
                                <button type="button" class="btn btn-outline-secondary">Главная</button>
                            </a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>


<?
$query = $query ?? [];
View::generateView(
    'view/comments/comments.php',
    [
        'commentsList' => $commentsList,
        'productId' => $product->getId(),
        'commentPage' => $commentPage,
        'totalPages' => $totalPages,
        'query' => $query
    ]
);
?>


<?
View::generateView(
    'view/layouts/footer.php'
);
?>