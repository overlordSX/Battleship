<?php

class CommentUtil
{
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