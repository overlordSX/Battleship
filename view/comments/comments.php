<?php
/**
 * @var CommentEntity[] $commentsList
 * @var int $productId
 * @var int $commentPage
 * @var int $totalPages
 */
?>
<hr>
<div class="container">
    <div class="row">
        <h4>Блок комментариев</h4>
    </div>


    <div class="card">
        <div class="card-body">
            <form action="/catalog/product/<?= $productId ?>/comment/new" method="post">
                <div class="row row-cols-auto">
                    <div class="col"><h5 class="card-title">Оставьте свой отзыв</h5></div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Отправить</button>
                    </div>

                </div>
                <div class="mb-1">
                    <label for="email" class="form-label">Ваша эл. почта</label>
                    <input type="email" class="form-control" name="email" placeholder="name@example.com">
                </div>
                <div class="mb-1">
                    <label for="comment" class="form-label">Напишите свой отзыв</label>
                    <textarea class="form-control" name="comment" rows="3"></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row row-cols-2">
                <?php
                /**
                 * @var CommentEntity[] $productList
                 * @var CommentEntity $comments
                 */
                foreach ($commentsList as $comments): ?>
                    <div class="card ">
                        <figure class="p-3 mb-0">
                            <blockquote class="blockquote">
                                <p><?= $comments->getComment() ?></p>
                            </blockquote>
                            <figcaption class="blockquote-footer mb-0 text-muted">
                                <?= $comments->getEmail() ?>
                            </figcaption>
                        </figure>
                    </div>
                <? endforeach; ?>
            </div>

            <?php
            $commentsPaginationView = new View();
            $commentsPaginationView->generateView(
                'view/pagination/pagination.php',
                [
                    'countOfPages' => $totalPages,
                    'currentPageNumber' => $commentPage,
                    'currentUrl' => 'catalog/product/' . $productId . "/comments"
                ]
            );

            ?>
        </div>
    </div>


</div>