<?php

class CommentUtil
{
    //TODO #4 тут нет поля статуса активности
    /**
     * @param $array
     * @return CommentEntity[]
     */
    public static function resultToListOfComments($array): array
    {
        $listOfComments = [];
        foreach ($array as $row) {
            $listOfComments[] = new CommentEntity(
                $row['email'],
                $row['comment'],
                $row['product_id'],
                $row['id']
            );
        }

        return $listOfComments;
    }
}