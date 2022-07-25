<?php

namespace Battleship\App\Database;



use Battleship\App\Database\Entity\AbstractEntity;
use Battleship\App\Database\Util\EntityUtil;
use Exception;

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
            'limit' => '',
            'offset' => ''
        ];

    protected array $queryParams = [];

    protected string $className;
    protected string $mainTableName;

    public function __construct(string $className = '')
    {
        if (!empty($className) and !is_subclass_of($className, AbstractEntity::class)) {
            throw new Exception('Класс не является подклассом абстрактной сущности');
        }

        $this->className = $className ?: '';
    }


    //TODO №1 либо как то так, либо where(может еще что то) отдельно, а остальное в query

    protected array $updateStmt =
        [
            'update' => '',
            'set' => '',
            'where' => [] // не нужен id нужен, его в where,
            ////TODO объединить все для инсерта и апдейта
        ];

    protected array $deleteStmt =
        [
            'deleteFrom' => '',
            'where' => [] // // не нужен id нужен, его в where
        ];

    protected array $where = [];

    public function clear(): static
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

        $this->query['insert'] =
            'insert into ' .
            $tableName .
            ' ' .
            $this->formatInsert($tableColumns) .
            ' values ' .
            $this->formatInsert(
                $this->makePlaceholders($params)
            );

        $this->queryParams = $this->extractParams($params);

        return $this->prepareAndExecute();
    }

    /**
     * @param string $tableName
     * @param array $insert ['column' => value]
     * @return bool
     */
    public function insert(string $tableName, array $insert): bool
    {
        $namedPlaceholders = $this->makeNamedPlaceholders(array_keys($insert));


        $this->query['insert'] =
            'insert into ' .
            $tableName .
            ' ' .
            $this->formatInsert([array_keys($insert)]) .
            ' values ' .
            $this->formatInsert([$namedPlaceholders]);

        $this->queryParams = $insert;

        return $this->prepareAndExecute();

    }

    public function update(string $tableName, string $attribute, mixed $oldValue, mixed $newValue): bool
    {
        //TODO realize update
        return true;
    }

    public function delete(string $tableName, string $attribute, mixed $value): bool
    {

        //TODO realize delete
        return true;
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

        array_walk_recursive($placeholders, function (&$item, $key) {
            $item = '?';
        });

        return $placeholders;
    }


    /**
     * Делает из массива с неограниченной вложенностью -> одномерный массив
     *
     * @param array $params
     * @return array
     */
    protected function extractParams(array $params): array
    {
        $result = [];
        array_walk_recursive($params, function ($item, $key) use (&$result) {
            $result[] = $item;
        });

        return $result;
    }

    /**
     * @param array $values [[1,2,3],...]
     * @return string (1,2,3),..
     */
    protected function formatInsert(array $values): string
    {
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


    public function from(string $table): static
    {
        $this->mainTableName = $table;
        $this->query['from'] .= ' FROM ' . $table . ' ';

        return $this;
    }

    public function join(
        string $secondaryTable,
        string $mainTableField,
        string $condition,
        string $secondaryTableField
    ): static {
        $this->query['join'] .= ' JOIN ' . $secondaryTable . ' ON ' . $this->mainTableName . '.' . $mainTableField .
            ' ' . $condition . ' ' .
            $secondaryTable . '.' . $secondaryTableField . ' ';


        return $this;
    }

    public function joinFromRow($joinRow): static
    {
        $this->query['join'] .= $joinRow;

        return $this;
    }


    public function where(
        string $attribute,
        string $condition,
        mixed $value
    ): static {
        $namedPlaceholder = ':' . $attribute . 'Where';
        $this->queryParams[$namedPlaceholder] = $value;

        $this->where[$attribute] = ['cond' => $condition, 'val' => $namedPlaceholder];

        return $this;
    }

    public function whereFromRow(string $whereRow): static
    {
        $this->query['where'] = $whereRow;

        return $this;
    }

    public function groupBy(string ...$fields): static
    {

        $this->query['groupBy'] = ' group by ';

        $count = 0;
        foreach ($fields as $field) {
            $count++;
            $this->query['groupBy'] .= $count > 1 ? ' , ' . $field : $field . ' ';
        }


        return $this;
    }

    public function having(string $attribute, string $condition, string $value): static
    {
        $this->query['having'] = ' having ' . $attribute . ' ' . $condition . ' ' . $value . ' ';


        return $this;
    }

    public function havingFromRow(string $havingRow): static
    {
        $this->query['having'] = ' having ' . $havingRow;

        return $this;
    }

    public function selectRow($selectRow): static
    {
        $this->query['select'] = $selectRow;

        return $this;
    }

    public function selectCountRows(): static
    {
        $this->query['select'] = 'select count(*) as count ';

        return $this;
    }

    public function select(string ...$fields): static
    {
        $this->query['select'] = 'select ';

        if (empty($fields)) {
            $this->query['select'] .= '* ';
        } else {
            $count = 0;
            foreach ($fields as $field) {
                $count++;
                $this->query['select'] .= $count > 1 ? ' , ' . $field : $field . ' ';
            }
        }

        return $this;
    }

    public function orderBy(string $attribute, string $order): static
    {
        if (empty($this->query['orderBy'])) {
            $this->query['orderBy'] .= ' order by ';
        } else {
            $this->query['orderBy'] .= ' , ';
        }

        $this->query['orderBy'] .= $attribute . ' ' . $order;

        return $this;
    }

    public function limit(string $limit): static
    {
        $this->query['limit'] = ' limit ' . $limit;

        return $this;
    }

    public function offset(string $offset): static
    {
        $this->query['offset'] = ' offset ' . $offset;

        return $this;
    }


    public function getQuery(): string
    {

        $whereArr = [];

        foreach ($this->where as $attr => $condAndVal) {
            $cond = $condAndVal['cond'];
            $val = $condAndVal['val'];
            $whereArr[] = $attr . ' ' . $cond . ' ' . $val;
        }

        if ($whereArr) {
            $this->query['where'] = ' where ' . implode(' and ', $whereArr) . ' ';
        }


        return implode('', $this->query);
    }

    /**
     * @param string $className
     * @param $params
     * @return AbstractEntity
     */
    public function fetch(): AbstractEntity
    {
        return EntityUtil::resultToEntity(
            $this->className,
            Database::queryFetchRow(
                $this->getQuery(),
                $this->queryParams
            )
        );
    }

    public function fetchToArray(): array
    {
        return Database::queryFetchRow($this->getQuery(), $this->queryParams);
    }

    /**
     * @param array $params
     * @return AbstractEntity[]
     */
    public function fetchAll(): array
    {
        $dbResult = Database::queryFetchAll($this->getQuery(), $this->queryParams) ?: [];

        return EntityUtil::resultToListOfEntities($this->className, $dbResult);
    }


    public function fetchAllToArray(): array
    {
        return Database::queryFetchAll($this->getQuery(), $this->queryParams);
    }

    public function fetchCount(): int
    {
        return Database::queryFetchRow($this->getQuery(), $this->queryParams)['count'];
    }

    public function prepareAndExecute(): bool
    {
        //var_dump($this->queryParams);
        return Database::prepareAndExecute($this->getQuery(), $this->queryParams);
    }


}