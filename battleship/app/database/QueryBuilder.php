<?php

class QueryBuilder
{
    protected array $query =
        [
            'select' => '',
            'insert' => '',
            'from' => '',
            'join' => '',
            'where' => '',
            'groupBy' => '',
            'having' => '',
            'orderBy' => '',
            'limitOffset' => ''
        ];

    protected string $className;

    public function __construct(string $className = '')
    {
        //еще можно чекнуть что className subclass_of(
        if (!empty($className) and !is_subclass_of($className, AbstractEntity::class)) {
            //var_dump($className);
            throw new Exception('Такого класса сущности нет');
        }

        $this->className = $className ?: '';
    }

    /*[
    //'codeMY' => ['=', 'qwe'],
    /*'/*code' => 'qwe',
    'id' => '1',
    'logic' => 'or',
    [
        'code' => 'qwe',
        'id' => '1',
    ],
    ],*/

    //TODO №1 либо как то так, либо where(может еще что то) отдельно, а остальное в query

    protected array $updateStmt =
        [
            'update' => '',
            'set' => '',
            'where' => [] // не нужен id нужен, его в where
        ];

    protected array $deleteStmt =
        [
            'deleteFrom' => '',
            'where' => [] // // не нужен id нужен, его в where
        ];

    protected array $where = [];

    public function clear(): self
    {
        foreach ($this->query as $key => $value) {
            $this->query[$key] = '';
        }
        return $this;
    }


    /**
     * @param string $tableName имя таблицы
     * @param array $tableColumns колонки таблицы
     * @param array $params [[?,?,?],...] => (?,?,?),...
     * @return bool true если запрос выполнен
     */
    public function insertMulti(string $tableName, array $tableColumns, array $params = []): bool
    {
        /*print_r(QueryBuilderUtil::getTableColumns($tableName));
        $tableColumns = !$tableColumns ? QueryBuilderUtil::getTableColumns($tableName) : $tableColumns;*/


        //print_r($tableColumns);


        $this->query['insert'] =
            'insert into ' .
            $tableName .
            ' ' .
            $this->formatInsert($tableColumns) .
            ' values ' .
            $this->formatInsert(
                $this->makePlaceholders($params)
            //$params
            );

        //echo $this->query['insert'];

        /*echo '<br>';
        print_r($this->extractParams([[1, 2, 3], [4, 5, 6], [7, 8, 9]]));
        echo '<br>';
        print_r($this->makePlaceholders([[1, 2, 3], [4, 5, 6], [7, 8, 9]]));*/


        return $this->prepareAndExecute(
            $this->extractParams($params)
        );
    }

    /**
     * @param string $tableName
     * @param array $insert ['column' => value]
     * @return bool
     */
    public function insert(string $tableName, array $insert): bool
    {

        //var_dump($this->formatInsert([array_keys($insert)]));
        //echo '<br>';
        $namedPlaceholders = $this->makeNamedPlaceholders(array_keys($insert));


        $this->query['insert'] =
            'insert into ' .
            $tableName .
            ' ' .
            $this->formatInsert([array_keys($insert)]) .
            ' values ' .
            $this->formatInsert([$namedPlaceholders]);

        //echo '<br>'. $this->query['insert'] . '<br>';

        return $this->prepareAndExecute($insert);

    }

    protected function makeNamedPlaceholders(array $keys): array
    {
        $named = [];
        foreach ($keys as $key) {
            $named[] = ':' . $key;
        }
        return $named;
    }

    protected function makePlaceholders(array $params): array
    {
        $placeholders = $params;

        array_walk_recursive($placeholders, function (&$item, $key){
            $item = '?';
        });

        return $placeholders;
    }


    /**
     * Ет я нашел :D
     *
     * @param array $params
     * @return array
     */
    protected function extractParams(array $params): array
    {
        $result = [];
        //use нужен чтобы можно было использовать родительскую переменную
        array_walk_recursive($params, function ($item, $key) use (&$result) {
            //echo '<br>' . $key . ' ' . $item . '<br>';
            $result[] = $item;
        });

        return $result;
    }

    /**
     * @param array $values [[1,2,3]]
     * @return string
     */
    protected function formatInsert(array $values): string
    {
        /*echo '<br>';
        var_dump($values);
        echo '<br>';*/
        $result = [];
        foreach ($values as $value) {
            $result[] = '(' . implode(', ', $value) . ')';
        }


        return implode(', ', $result);
    }

    public function insertFromRow(string $insertRow, array $params = []): bool
    {
        $this->query['insert'] = $insertRow;
        return $this->prepareAndExecute($params);
    }


    public function from(string $table): self
    {
        $this->query['from'] .= ' FROM ' . $table . ' ';

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

    public function where2(string $attribute, string $condition,string $namedPlaceholder): self
    {
        $this->where[$attribute] = ['cond' => $condition, 'val' => $namedPlaceholder];
        /*if (!$this->query['where']) {
            $this->query['where'][$attribute] = [$condition, $value];
        }*/

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

    //TODO разнести на отдельные
    public function limitOffset(string $limit, string $offset): self
    {
        $this->query['limitOffset'] = ' limit ' . $limit . ' offset ' . $offset;

        return $this;
    }


    public function getQuery(): string
    {
        //$strQuery = $this->query;
        /*if ($strQuery['where']) {
            $strQuery['where'] = ' where ' . implode(' and ', $this->query['where']) . ' ';
        }*/

        $whereArr = [];

        foreach ($this->where as $attr => $condAndVal) {
            $cond = $condAndVal['cond'];
            $val = $condAndVal['val'];
            $whereArr[] = $attr . ' ' . $cond . ' ' . $val;
            //var_dump($value);
        }

        if ($whereArr){
            $this->query['where'] = ' where ' . implode(' and ', $whereArr) . ' ';
        }



        return implode('', $this->query);
    }

    /**
     * @param string $className
     * @param $params
     * @return AbstractEntity
     */
    public function fetch($params = []): AbstractEntity
    {


        return EntityUtil::resultToEntity($this->className, Database::queryFetchRow($this->getQuery(), $params));
    }

    public function fetchNoConvertToEntity($params = []): array
    {
        //TODO тута что нибудь типо магии, хоп хоп и объект вернется
        // плюс переименовать в fetch()

        return Database::queryFetchRow($this->getQuery(), $params);
    }

    /**
     * @param $params
     * @return AbstractEntity[]
     */
    public function fetchAll($params = []): array
    {
        echo '<br>' . implode('', $this->query) . '<br>';

        $dbResult = Database::queryFetchAll($this->getQuery(), $params) ?: [];

        echo $this->getQuery() . '<br>';
        var_dump($dbResult);
        echo ' это до меня было <br>';

        //TODO доделать как fetch()
        return EntityUtil::resultToListOfEntities($this->className, []);
    }

    //TODO сделать выше так же
    public function fetchAllToArray($params = []): array
    {
        return Database::queryFetchAll($this->getQuery(), $params);
    }


    public function prepareAndExecute($params = []): bool
    {
        return Database::prepareAndExecute($this->getQuery(), $params);
    }

    public function prepareAndInsert($params = []): int
    {

        return 1;
    }

}