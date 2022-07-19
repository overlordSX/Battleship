<?php

class QueryBuilder
{
    protected array $query =
        [
            'select' => '',
            'from' => '',
            'join' => '',
            'where' => '',
            'groupBy' => '',
            'having' => '',
            'orderBy' => '',
            'limitOffset' => ''
        ];

    protected array $executeParams = [];


    public function clear(): void
    {
        foreach ($this->query as $key => $value) {
            $this->query[$key] = '';
        }
    }

    public function from(string $table): self
    {
        $this->query['from'] .= ' FROM ' . $table;

        return $this;
    }

    public function join(string $mainTable, string $secondaryTable, string $mainTableField, string $condition, string $secondaryTableField): self
    {
        $this->query['join'] .= ' JOIN ' . $secondaryTable . ' ON ' . $mainTable . '.' . $mainTableField .
            ' ' . $condition . ' ' .
            $secondaryTable . '.' . $secondaryTableField . ' ';

        
        return $this;
    }

    public function joinFromRow($joinRow): self
    {
        $this->query['join'] .= $joinRow;

        return $this;
    }

    public function where(string $attribute, string $condition, $value): self
    {
        $this->query['where'] = ' where ' . $attribute . ' ' . $condition . ' ' . $value . ' ';

        return $this;
    }

    public function whereFromRow(string $whereRow): self
    {
        $this->query['where'] = $whereRow;

        return $this;
    }

    public function groupBy(string ...$fields): self
    {
        /*if (empty($this->query['groupBy'])) {

        }*/

        $this->query['groupBy'] = ' group by ';

        $count = 0;
        foreach ($fields as $field) {
            $count++;
            $this->query['groupBy'] .= $count > 1 ? ' , ' . $field : $field . ' ';
        }


        return $this;
    }

    public function having(string $attribute, string $condition, string $value): self
    {
        $this->query['having'] = ' having ' . $attribute . ' ' . $condition . ' ' . $value . ' ';


        return $this;
    }

    public function havingFromRow(string $havingRow): self
    {
        $this->query['having'] = ' having ' . $havingRow;

        return $this;
    }

    public function selectRow($selectRow): self
    {
        $this->query['select'] = $selectRow;

        return $this;
    }

    public function select(string ...$fields): self
    {
        $this->query['select'] = 'select ';


        $count = 0;
        foreach ($fields as $field) {
            $count++;
            $this->query['select'] .= $count > 1 ? ' , ' . $field : $field . ' ';
        }

        return $this;
    }

    public function orderBy(string $attribute, string $order): self
    {
        if (empty($this->query['orderBy'])) {
            $this->query['orderBy'] .= ' order by ';
        } else {
            $this->query['orderBy'] .= ' , ';
        }

        $this->query['orderBy'] .= $attribute . ' ' . $order;

        return $this;
    }

    public function limitOffset(string $limit, string $offset): self
    {
        $this->query['limitOffset'] = ' limit ' . $limit . ' offset ' . $offset;

        return $this;
    }


    public function getQuery(): string
    {
        return implode('', $this->query);
    }

    public function fetchRow($params = []): array
    {

        return Database::queryFetchRow(implode('', $this->query), $params);
    }
    public function fetchAll($params = []): array
    {
        //var_dump($this->query);
        //echo '<script>alert(123)</script>';

        return Database::queryFetchAll(implode('', $this->query), $params);
    }
}