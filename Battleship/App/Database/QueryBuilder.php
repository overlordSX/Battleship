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
            'update' => '',
            'set' => '',
            'delete' => '',
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

    protected array $where = [];
    protected array $set = [];

    protected string $className;
    protected string $mainTableName;

    /**
     * @throws Exception
     */
    public function __construct(string $className = '')
    {
        if (!empty($className) and !is_subclass_of($className, AbstractEntity::class)) {
            throw new Exception('Класс не является подклассом абстрактной сущности');
        }
        $this->className = $className ?: '';
    }


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


    /**
     * @param string $tableName
     * @param array $conditions [attr => [cond => val]]
     * @param array $sets [attr => newVal]
     * @return bool
     */
    public function update(string $tableName, array $conditions, array $sets): bool
    {
        $this->mainTableName = $tableName;
        $this->query['update'] = 'update ' . $this->mainTableName . ' ';

        foreach ($conditions as $condition) {
            foreach ($condition as $attr => $condValue) {
                foreach ($condValue as $cond => $value) {
                    $this->where($attr, $cond, $value);
                }

            }
        }

        foreach ($sets as $set) {
            foreach ($set as $attr => $newValue) {
                $this->set(
                    $attr,
                    $newValue
                );
            }

        }

        return $this->prepareAndExecute();
    }

    protected function set(string $attribute, string|int $newValue): static
    {

        $namedPlaceholder = ':' . str_replace('.', '',$attribute) . 'Set';
        $this->set[$attribute] = $namedPlaceholder;

        $this->queryParams[$namedPlaceholder] = $newValue;

        return $this;
    }

    public function delete(string $tableName, string $attribute, string $condition, mixed $value): bool
    {
        $this->query['delete'] = 'delete from ' . $tableName . ' ';
        $this->where($attribute, $condition, $value);


        return $this->prepareAndExecute();
    }

    protected function makeNamedPlaceholders(array $keys): array
    {
        $named = [];
        foreach ($keys as $key) {
            $named[] = ':' . str_replace('.', '', $key);
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

    /**
     * подготавливает set для вставки в update
     * @param array $data
     * @return string
     */
    protected function prepareSet(array $data): string
    {
        $setArr = [];
        foreach ($data as $key => $value) {
            $setArr[] = ' ' . $key . '=' . $value;
        }

        return implode(', ', $setArr);
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
    ): static
    {
        $this->query['join'] .= ' JOIN ' . $secondaryTable . ' ON ' . $this->mainTableName . '.' . $mainTableField
            . ' ' . $condition . ' '
            . $secondaryTable . '.' . $secondaryTableField . ' ';


        return $this;
    }

    public function joinFromRow($joinRow): static
    {
        $this->query['join'] .= ' ' . $joinRow . ' ';

        return $this;
    }


    public function where(
        string     $attribute,
        string     $condition,
        string|int $value
    ): static
    {

        $namedPlaceholder = ':' .  str_replace('.', '',$attribute) . 'Where';
        $this->queryParams[$namedPlaceholder] = $value;

        if ($this->where) {
            $this->where['&&||'] = 'and';
        }
        $this->where[$attribute] = ['cond' => $condition, 'val' => $namedPlaceholder];

        return $this;
    }

    protected function assocToList($array): array
    {
        $temp = [];
        foreach ($array as $item) {
            $temp[] = $item;
        }
        return $temp;
    }

    public function whereBrackets(callable $callback, string|int $param, string $condition = 'and'): static
    {
        if ($this->where) {
            //$this->where = [array_values($this->where)];
            $this->where['&&||'] = $condition;
        }


        $queryBuilder = new QueryBuilder();
        $callback($queryBuilder, $param);
        $bracketsWhere = $queryBuilder->where;

        $this->where['()'] = $bracketsWhere;

        $this->queryParams = array_merge($this->queryParams, $queryBuilder->queryParams);

        return $this;
    }

    public function orWhere(
        string     $attribute,
        string     $condition,
        string|int $value
    ): static
    {

        $this->where['&&||'] = 'or';
        $namedPlaceholder = ':' .  str_replace('.', '',$attribute) . 'WhereOr';
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

    protected function selectCountRows(): static
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

        $this->query['orderBy'] .= $attribute . ' ' . $order . ' ';
        return $this;
    }

    public function limit(int $limit): static
    {
        $this->query['limit'] = ' limit ' . $limit;

        return $this;
    }

    public function offset(int $offset): static
    {
        $this->query['offset'] = ' offset ' . $offset;

        return $this;
    }


    public function getQuery(): string
    {

        $setArr = [];

        foreach ($this->set as $attr => $newValue) {
            $setArr[] = $attr . ' = ' . $newValue;
        }

        if ($setArr) {
            $this->query['set'] = ' set ' . implode(' , ', $setArr) . ' ';
        }

        //TODO из этого бы сделать рекурсивную функцию
        $makeStr = [];
        foreach ($this->where as $attr => $elem) {

            if ($attr === '&&||') {
                $makeStr[] = $elem;
                continue;
            }

            if ($attr === '()') {
                $makeStr[] = '(';
                foreach ($elem as $attrIn => $condAndValIn) {
                    if ($attrIn === '&&||') {
                        $makeStr[] = $condAndValIn;
                        continue;
                    }
                    $cond = $condAndValIn['cond'];
                    $val = $condAndValIn['val'];
                    $makeStr[] = $attrIn . ' ' . $cond . ' ' . $val;
                }
                $makeStr[] = ')';
                continue;
            }

            $cond = $elem['cond'];
            $val = $elem['val'];
            $makeStr[] = $attr . ' ' . $cond . ' ' . $val;

        }


        if ($makeStr) {
            $this->query['where'] = ' where ' . implode(' ', $makeStr) . ' ';
        }

        if ($this->query['from'] && !$this->query['select']) {
            $this->query['select'] = ' select * ';
        }

        return implode('', $this->query);
    }

    /**
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
        $this->selectCountRows();

        return Database::queryFetchRow($this->getQuery(), $this->queryParams)['count'];
    }

    public function prepareAndExecute(): bool
    {
        return Database::prepareAndExecute($this->getQuery(), $this->queryParams);
    }


}