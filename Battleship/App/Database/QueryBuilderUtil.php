<?php

class QueryBuilderUtil
{
    public static function getTableColumns(string $tableName): array
    {
        $queryBuilder = new QueryBuilder();

        $columns = $queryBuilder
            //->selectRow('SHOW columns from ' . $tableName)->fetchAll();
            ->clear()
            ->from($tableName)
            ->selectRow('SHOW columns')
            //->getQuery();
            ->fetchAll();
        print_r($columns);
        $requiredFields = [];
        foreach ($columns as $row) {
            //var_dump($row['Extra'] !== 'auto_increment');
            if (empty($row['Extra'])) {
                $requiredFields[] = $row['Field'];
            }
        }

        return [$requiredFields];
    }



}