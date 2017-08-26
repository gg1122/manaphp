<?php
namespace ManaPHP\Model;

interface CriteriaInterface
{
    /**
     * @param string|array $fields
     *
     * @return static
     */
    public function select($fields);

    /**
     * @param array $expr
     *
     * @return static
     */
    public function aggregate($expr);

    /**
     * Appends a condition to the current conditions using a AND operator
     *
     *<code>
     *    $builder->andWhere('name = "Peter"');
     *    $builder->andWhere('name = :name: AND id > :id:', array('name' => 'Peter', 'id' => 100));
     *</code>
     *
     * @param string|array           $condition
     * @param int|float|string|array $bind
     *
     * @return static
     */
    public function where($condition, $bind = []);

    /**
     * Appends a BETWEEN condition to the current conditions
     *
     *<code>
     *    $builder->betweenWhere('price', 100.25, 200.50);
     *</code>
     *
     * @param string           $expr
     * @param int|float|string $min
     * @param int|float|string $max
     *
     * @return static
     */
    public function betweenWhere($expr, $min, $max);

    /**
     * Appends a NOT BETWEEN condition to the current conditions
     *
     *<code>
     *    $builder->notBetweenWhere('price', 100.25, 200.50);
     *</code>
     *
     * @param string           $expr
     * @param int|float|string $min
     * @param int|float|string $max
     *
     * @return static
     */
    public function notBetweenWhere($expr, $min, $max);

    /**
     * Appends an IN condition to the current conditions
     *
     *<code>
     *    $builder->inWhere('id', [1, 2, 3]);
     *</code>
     *
     * @param string                           $expr
     * @param array|\ManaPHP\Db\QueryInterface $values
     *
     * @return static
     */
    public function inWhere($expr, $values);

    /**
     * Appends a NOT IN condition to the current conditions
     *
     *<code>
     *    $builder->notInWhere('id', [1, 2, 3]);
     *</code>
     *
     * @param string                           $expr
     * @param array|\ManaPHP\Db\QueryInterface $values
     *
     * @return static
     */
    public function notInWhere($expr, $values);

    /**
     * @param string|array $expr
     * @param string       $like
     *
     * @return static
     */
    public function likeWhere($expr, $like);

    /**
     * Sets a ORDER BY condition clause
     *
     *<code>
     *    $builder->orderBy('Robots.name');
     *    $builder->orderBy(array('1', 'Robots.name'));
     *</code>
     *
     * @param string|array $orderBy
     *
     * @return static
     */
    public function orderBy($orderBy);

    /**
     * Sets a LIMIT clause, optionally a offset clause
     *
     *<code>
     *    $builder->limit(100);
     *    $builder->limit(100, 20);
     *</code>
     *
     * @param int $limit
     * @param int $offset
     *
     * @return static
     */
    public function limit($limit, $offset = null);

    /**
     * @param int $size
     * @param int $page
     *
     * @return static
     */
    public function page($size, $page = null);

    /**
     * Sets a GROUP BY clause
     *
     *<code>
     *    $builder->groupBy(array('Robots.name'));
     *</code>
     *
     * @param string|array $groupBy
     *
     * @return static
     */
    public function groupBy($groupBy);

    /**
     * @param callable|string $indexBy
     *
     * @return static
     */
    public function indexBy($indexBy);

    /**
     * @param array|int $options
     *
     * @return static
     */
    public function cache($options);

    /**
     * @param string $field
     *
     * @return array
     */
    public function distinctField($field);

    /**
     * @param int $size
     * @param int $page
     *
     * @return \ManaPHP\Paginator
     */
    public function paginate($size, $page = null);

    /**
     * @return bool
     */
    public function exists();

    /**
     * @param bool $asModel
     *
     * @return array|\ManaPHP\ModelInterface|false
     */
    public function fetchOne($asModel = false);

    /**
     * @param bool $asModel
     *
     * @return array|\ManaPHP\ModelInterface[]
     */
    public function fetchAll($asModel = false);

    /**
     * @param $fieldValues
     *
     * @return int
     */
    public function update($fieldValues);

    /**
     * @return int
     */
    public function delete();

    /**
     * @param bool $forceUseMaster
     *
     * @return static
     */
    public function forceUseMaster($forceUseMaster = true);
}