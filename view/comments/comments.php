<?
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
                    <div class="col">
                        <h5 class="card-title">Оставьте свой отзыв</h5>
                    </div>
                    <div class="col">
                        <button type="submit" class="btn btn-primary" tabindex="3">Отправить</button>
                    </div>
                </div>

                <div class="row">
                    <?
                    $fieldNames =
                        [
                            'email' => 'Ваша эл. почта',
                            'comment' => 'Напишите свой отзыв'
                        ];

                    if (isset($fieldsErrors)) { ?>
                        <div class="row justify-content-start">
                            <div class="col-6">
                                <? foreach ($fieldsErrors as $field => $message) { ?>
                                    <div class="badge bg-danger text-wrap mt-2">
                                        <p class="fs-6 m-0"><?= $fieldNames[$field] . ': ' . $message ?></p>
                                    </div>
                                    <?
                                } ?>
                            </div>
                        </div>
                        <?
                    }
                    ?>
                </div>
                <div class="mb-1">
                    <label for="email" id="labelEmail" class="form-label"><?= $fieldNames['email'] ?></label>
                    <input type="email" class="form-control" id="labelEmail" name="email" tabindex="1"
                           placeholder="name@example.com"
                           value="<?= $trimPost['email'] ?? '' ?>">
                </div>
                <div class="mb-1">
                    <label for="comment" id="labelEmail" class="form-label"><?= $fieldNames['comment'] ?></label>
                    <textarea id="labelComment" class="form-control" name="comment" tabindex="2"
                              rows="3"><?= $trimPost['comment'] ?? '' ?></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row row-cols-2">
                <?
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

            <?
            View::generateView(
                'view/pagination/pagination.php',
                [
                    'countOfPages' => $totalPages,
                    'currentPageNumber' => $commentPage,
                    'currentUrl' => 'catalog/product/' . $productId . "/comments",
                    'getQuery' => $_GET
                ]
            );
            ?>
        </div>
    </div>
</div>