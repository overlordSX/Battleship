<?
/**
 * @var CommentEntity[] $nonModerateComments
 * @var int $currentAdminPage
 * @var int $totalPages
 */
?>

<? View::generateView('view/layouts/header.php', ['title' => "Админ панель", 'activeLink' => 'admin']); ?>


    <div class="container pt-2">
        <div class="col">
            <div class="row row-cols-2 pb-3">
                <?
                $tableButtons =
                    [
                        'Создать таблицу Отзывы' => '/admin/messages/create',
                        'Создать таблицу Товары' => '/admin/catalog/create',
                        'Удалить таблицу Отзывы' => '/admin/messages/drop',
                        'Удалить таблицу Товары' => '/admin/catalog/drop'
                    ]
                ?>
                <? foreach ($tableButtons as $title => $href) { ?>
                    <div class="col-3 pt-1">
                        <a role="button" class="btn btn-warning" href="<?= $href ?>"><?= $title ?></a>
                    </div>
                <? } ?>
            </div>

            <div class="row pb-2">
                <div class="col">Комментарий</div>
                <div class="col">Почта</div>
                <div class="col">Модерация</div>
                <hr>
            </div>

            <?
            /**
             * @var CommentEntity $comment
             */
            foreach ($nonModerateComments as $comment) { ?>
                <div class="row pb-1">
                    <div class="col"><?= $comment->getComment() ?></div>
                    <div class="col"><?= $comment->getEmail() ?></div>
                    <div class="col">
                        <form action="" method="post">
                            <input type="hidden" name="commentId" value="<?= $comment->getId() ?>">
                            <button type="submit" class="btn btn-success">Промодерировать</button>
                        </form>
                    </div>
                </div>
            <? } ?>
        </div>
        <?
        View::generateView(
            'view/pagination/pagination.php',
            [
                'countOfPages' => $totalPages,
                'currentPageNumber' => $currentAdminPage,
                'currentUrl' => 'admin',
                'getQuery' => $_GET
            ]
        );
        ?>
    </div>


<? View::generateView('view/layouts/footer.php'); ?>