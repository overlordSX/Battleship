<?php

class AdminController
{
    public const ADMIN_PAGE_SIZE = 5;

    public static function adminPanel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $currentAdminPage = $_GET['page'] ?? 1;
            $offset = ($currentAdminPage - 1) * self::ADMIN_PAGE_SIZE;
            $totalNotActiveComments = Comments::getCountOfAllNotActiveComments();

            /**
             * @var ProductEntity $product
             */
            $nonModerateComments = Comments::getAllCommentsWithLimitOffsetNonModerate(self::ADMIN_PAGE_SIZE, $offset);

            View::generateView(
                'view/admin/admin.php',
                [
                    'nonModerateComments' => $nonModerateComments,
                    'currentAdminPage' => $currentAdminPage,
                    'totalPages' => ceil($totalNotActiveComments / self::ADMIN_PAGE_SIZE),
                ]
            );


        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $commentId = $_POST['commentId'] ?? null;

            //todo тут метод проверки существования id и выброс ошибки

            Comments::moderateComment($commentId);
            header('Location: /admin');

        }
    }

}