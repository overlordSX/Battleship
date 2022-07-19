<?php

class AdminController implements ControllerInterface
{
    public const ADMIN_PAGE_SIZE = 5;

    public function adminPanel(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $currentAdminPage = $_GET['page'] ?? 1;
            $offset = ($currentAdminPage - 1) * self::ADMIN_PAGE_SIZE;
            $qb = new QueryBuilder();
            $totalNotActiveComments = $qb->
            from('comments')->
            where('activity_status', '=', 'false')->
            selectRow('select count(*) as total_comments')->
            fetchRow()['total_comments'];


            $nonModerateComments = [];
            $qb->clear();

            $nonModerateComments =
                $qb->
                from('comments')->
                where('activity_status', '=', '0')->
                select('*')->
                limitOffset(':limit', ':offset')->
                fetchAll(
                    [
                        'limit' => self::ADMIN_PAGE_SIZE,
                        'offset' => $offset
                    ]
                );

            $nonModerateComments = EntityUtil::resultToListOfEntities("CommentEntity", $nonModerateComments);

            View::generateView(
                'view/admin/admin.php',
                [
                    'nonModerateComments' => empty($nonModerateComments) ? [] : $nonModerateComments,
                    'currentAdminPage' => $currentAdminPage,
                    'totalPages' => ceil($totalNotActiveComments / self::ADMIN_PAGE_SIZE),
                ]
            );


        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $commentId = $_POST['commentId'] ?? null;

            Comments::moderateComment($commentId);
            header('Location: /admin');

        }
    }

}