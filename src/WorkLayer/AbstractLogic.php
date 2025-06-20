<?php
/**
 * @file   : AbstractLogic.php
 * @time   : 13:56
 * @date   : 2025/4/18
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace WanRen\WorkLayer;

use Closure;
use think\Collection;
use think\db\BaseQuery;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\Model;
use WanRen\Data\ArrayHelper;
use WanRen\Data\StringHelper;
use WanRen\Environment\ConfigHelper;
use WanRen\IO\LoggerHelper;

/**
 * 抽象逻辑层基类
 *
 * 1->包含了以 xxxEntity命名的通用的CRUD数据库操作方法。派生类可以调用这些方法，并加入自己的业务逻辑。
 * 2->其中以 xxResult命名的方法，其返回值类型为统一数据结构的信息传递数组，可以直接用于前端（或者API接口）展示。
 * （建议派生类实现自己的方法时，如果返回值类型也为统一数据结构的信息传递数组，那么方法命名也遵循此规范为 xxxResult）
 *
 * 其他注意事项：
 * 1->参数$where的格式说明,需要注意传入的格式：
 * 1.1->字符串类型：直接传入条件字符串即可。
 * 1.2->一维数组类型：['字段名1' => 值1, '字段名2' => 值2,...]。类似如下形式：['id' => 1, 'name' => '张三']。
 * 1.3->索引多维数组类型：[[字段名1,操作符（默认为=，可以省略）,待查询的值],[字段名2,操作符,待查询的值],...]。类似如下：[['id','=',5],['name','like','%大%'],...]
 * 1.4->关联多维数组类型，主要用在Or条件的查询中(目前以__or__ 开头的关键字作为Or查询的标识。也可以在.env文件中通过参数`WHERE_CONDITION_OR_PREFIX`配置这个OR查询标识符前缀为其他字符串。)：["__or__1"=>[字段名1,操作符（默认为=，可以省略）,待查询的值],"__or__2"=>[字段名2,操作符,待查询的值],...]。
 *  当然，每个OR对应一个数组元素，可以是一维的， 也可以是多维的，比如：["__or__1"=>[[字段名1,操作符,待查询的值],[字段名2,操作符,待查询的值]]。这样字段1和字段2的关系是AND，他们组合之后再跟其他条件进行OR。
 *
 * 2->参数$where的格式示例：
 * 2.1->字符串类型：'id=1 and name="张三"'。
 * 2.2->一维数组类型：['id' => 1, 'name' => '张三']。
 * 2.3->索引多维数组类型：[['id','=',5],['name','like','%大%']]。
 * 2.4->复杂的索引多维数组类型：[[['id','=',5],['name','like','小%']],[['grade','=',5],['name','like','%小']]] （最高level的各个元素生成condition的会自动加括号。）
 * 2.5->OR关联多维数组类型：["__or__1"=>['id','=',5],'__or__2'=>['name','like','%大%']]
 * 2.6->复杂的OR条件查询：["__or__1"=>[['id','=',5],['name','like','%小%']],'__or__2'=>[['id','=',6],['name','like','%大%']]]
 * 其生成的sql的where子句为：WHERE ( `id` = 5 AND `name` LIKE '%小%' ) OR ( `id` = 6 AND `name` LIKE '%大%' )
 *
 * 3->【参数$where的特别说明】：
 * 因为ThinkORM 从3.0.20到 3.0.21版本，对whereOr的支持有所改变：
 * 3.1-> 版本3.0.20之前是：whereOr($where)：$where 会跟外部其他条件进行 OR 连接。
 * 3.1-> 版本3.0.21之后是：whereOr($where)：$where 会跟外部其他条件进行 AND 连接，而$where内部各个子条件才进行 OR 连接。
 * 因此为了避免混淆，遇到Or的情况，建议使用闭包或者拼写字符串的方式进行条件组合。
 *
 *
 */
abstract class AbstractLogic
{
    private Model $model;
    private bool $useIsolatedModeInOperations;

    private BaseQuery $baseQuery;

    private static string $conditionOrPrefix = "";
    private static string $conditionAndPrefix = "";

    /**
     * 构造函数，主要作用是实例化模型对象，传入的选填的参数为(不带前缀的)数据库表名或模型对象。
     * 创建模型对象时：如果派生类的类名称，可以跟数据库表对应，就可以省略传入表名。
     * @param string|array $connectionNameOrOptions 数据库连接名称或更多配置项（如果不传，则使用默认的数据库连接；如果传入字符串，则表示选用指定的数据库连接；如果传入数组，则表示配置项（在配置项中可以通过键名connectionName或connection指定数据库连接名称）
     * @param bool|string|Model $modelInfoOrIsolatedMode 数据库表名或模型对象；如果使用默认的数据库表名，此参数也可以传入true，则使用隔离模式（此时自动忽略第二个参数）。
     * @param bool $useIsolatedModeInOperations 是否在连续多次地动作中使用独立的模型状态。默认为false，即多次使用同一个logic实例会共享查询状态。
     * （共享模式下，前次动作对模型的操作会影响到下次的动作。比如：
     * 连续getEntity多次的时候，如果没有使用隔离模式，ThinkORM会在第后一次调用时where条件的时候叠加上前一次的where条件；让开发者感觉“莫名其妙”。
     * 因此这个时候就需要使用隔离模式。）
     * =>特别注意：如果创建了一个logic实例，多次调用的时候，也可以粗放地将此参数设置为true，这样使用隔离的模式，每次调用logic都是“全新”的。
     */
    public function __construct(bool|string|Model $modelInfoOrIsolatedMode = "", bool $useIsolatedModeInOperations = false, string|array $connectionNameOrOptions = "")
    {
        if (is_bool($modelInfoOrIsolatedMode)) {
            $useIsolatedModeInOperations = $modelInfoOrIsolatedMode;
            $modelInfoOrIsolatedMode     = "";
        }

        $this->useIsolatedModeInOperations = $useIsolatedModeInOperations;
        $this->setModelDetails($modelInfoOrIsolatedMode, $connectionNameOrOptions);
    }

    /**
     * 传递给Where条件的时候，如果是 OR 条件，可自定义的前缀，默认是 "__or__"。
     * @return string
     */
    private static function getConditionOrPrefix(): string
    {
        if (empty(self::$conditionOrPrefix)) {
            self::$conditionOrPrefix = ConfigHelper::getEnv("WHERE_CONDITION_OR_PREFIX", "__or__");
        }
        return self::$conditionOrPrefix;
    }

    /**
     * 传递给Where条件的时候，如果是 AND 条件，可自定义的前缀，默认是 "__and__"。
     * @return string
     */
    private static function getConditionAndPrefix(): string
    {
        if (empty(self::$conditionAndPrefix)) {
            self::$conditionAndPrefix = ConfigHelper::getEnv("WHERE_CONDITION_AND_PREFIX", "__and__");
        }
        return self::$conditionAndPrefix;
    }

    /**
     * 获取模型对象(model有可能为null，使用的时候需要判断)
     * @return mixed
     */
    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * 设置模型对象
     * (因为模型支持连贯操作，所以模型对象对历史条件具有记忆功能。因此，如果需要一个不含有历史条件的模型对象，可以重新实例化一个模型对象。)
     * @param mixed $modelOrModelName
     * @param string|array $connectionNameOrOptions
     * @return void
     */
    private function setModelDetails(mixed $modelOrModelName = "", string|array $connectionNameOrOptions = ""): void
    {
        //1-> 确定模型对象的名字
        $modelName = "";
        if (is_string($modelOrModelName)) {
            $modelName = $modelOrModelName;
            if (empty($modelName)) {
                $modelName = str_replace('\\', '/', static::class);
                $modelName = basename($modelName);
                $modelName = str_replace('Logic', '', $modelName);
            }
        }

        if ($modelOrModelName instanceof Model) {
            $this->model = $modelOrModelName;
            $modelName   = $modelOrModelName->getName();
        }

        //2-> 确定数据库连接配置
        $connection = "";
        if (is_string($connectionNameOrOptions)) {
            $connection = $connectionNameOrOptions;
        }

        if (is_array($connectionNameOrOptions)) {
            //目前只支持从$options中获取数据库连接配置
            $connection = $connectionNameOrOptions['connection'] ?? $connectionNameOrOptions['connectionName'] ?? '';
        }

        //3-> 创建查询对象
        if ($connection) {
            /** @noinspection all */
            $this->baseQuery = Db::connect($connection)->name($modelName)->strict(false);
        } else {
            /** @noinspection all */
            $this->baseQuery = Db::name($modelName)->strict(false);
        }
    }

    /**
     * @return void
     */
    private function resetBaseQuery(): void
    {
        // strict(false) 关闭严格模式。当跟数据库交互不存在的字段时，不会报错，其会被自动忽略。
        $this->baseQuery = $this->baseQuery->removeOption()->strict(false);
    }

    /**
     * 获取最后一条SQL语句
     * @return string
     */
    public function getLastSql(): string
    {
        return $this->baseQuery->getLastSql();
    }

    /**
     * 获取单条数据
     * @param string|Closure|array $where
     * @param string $orderBy
     * @param string $fields
     * @param bool $result_as_array 是否返回数组形式的结果
     * @return array|mixed|null
     */
    public function getEntity(string|Closure|array $where = [], string $orderBy = "", string $fields = "", bool $result_as_array = true): mixed
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        try {
            $query  = $this->baseQuery->field($fields)->order($orderBy);
            $query  = $this->prepareWhere($query, $where);
            $result = $query->find();

            if ($result_as_array && $result instanceof Model) {
                return $result->toArray();
            }

            return $result;
        } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
            return null;
        }
    }

    /**
     * 获取不包含数据（数据为0或“”），只包含数据结构的空实体。
     * @return array
     */
    public function getEmptyEntity(): array
    {
        /** @noinspection all */
        $fields = $this->baseQuery->getFields();
        $entity = [];
        foreach ($fields as $key => $value) {
            //默认字段类型为字符串
            $entity[$key] = "";

            //获取字段类型
            $fieldType = $value['type'];

            //1->日期类型默认值设为0000-00-00 00:00:00
            /** @noinspection all */
            if (str_contains($fieldType, 'year') || $fieldType === 'timestamp' ||
                $fieldType == 'datetime' || $fieldType == 'date' || $fieldType == 'time'
            ) {
                $entity[$key] = 0;
            }

            //2->数字类型默认值设为0
            if (str_contains($fieldType, 'int') || str_contains($fieldType, 'float') ||
                str_contains($fieldType, 'double') || str_contains($fieldType, 'decimal') ||
                str_contains($fieldType, 'bit')
            ) {
                $entity[$key] = 0;
            }

            //3->其他类型，可以继续补充
            //...
        }

        return $entity;
    }

    /**
     * 获取数据条数
     * @param string|Closure|array $where
     * @param string $field
     * @return int|null
     */
    public function getEntityCount(string|Closure|array $where = [], string $field = ""): ?int
    {
        if (empty($field)) {
            $field = "*";
        }

        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        $query = $this->baseQuery;
        $query = $this->prepareWhere($query, $where);
        return $query->count($field);
    }

    /**
     * 获取数据合计值
     * @param string $field
     * @param string|Closure|array $where
     * @return float
     */
    public function getEntitySum(string $field = "", string|Closure|array $where = []): float
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        $query = $this->baseQuery;
        $query = $this->prepareWhere($query, $where);
        return $query->sum($field);
    }

    /**
     * 获取数据平均值
     * @param string $field
     * @param string|Closure|array $where
     * @return float
     */
    public function getEntityAvg(string $field, string|Closure|array $where = []): float
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        $query = $this->baseQuery;
        $query = $this->prepareWhere($query, $where);
        return $query->avg($field);
    }

    /**
     * 获取数据最大值
     * @param string $field
     * @param string|Closure|array $where
     * @return float
     */
    public function getEntityMax(string $field, string|Closure|array $where = []): float
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        $query = $this->baseQuery;
        $query = $this->prepareWhere($query, $where);
        return $query->max($field);
    }

    /**
     * 获取数据最小值
     * @param string $field
     * @param string|Closure|array $where
     * @return float
     */
    public function getEntityMin(string $field, string|Closure|array $where = []): float
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        $query = $this->baseQuery;
        $query = $this->prepareWhere($query, $where);
        return $query->min($field);
    }

    /**
     * 获取数据列表
     * @param string|Closure|array $where
     * @param string $limit
     * @param string $orderBy
     * @param string $fields
     * @param array $options 更多可选（不常用）的参数信息，包括：
     * 1-> result_as_array: 是否返回数组形式的结果，默认为true。
     * 2-> ignore_where_condition_names : 忽略的where条件字段名数组（即强制要求不参与where条件的字段名）。
     * 如果包含了"__no_default__"这个特殊字符串的话，则不会添加默认的忽略条件。本属性只$where参数中的[$key=>$value]类型的元素有忽略作用。
     * 3-> ...(其他可选参数)
     * @return array|Collection|null
     */
    public function getEntities(string|Closure|array $where = [], mixed $limit = "", string $orderBy = "", string $fields = "", array $options = []): array|Collection|null
    {
        $result_as_array           = $options['result_as_array'] ?? true;
        $ignoreWhereConditionNames = $options['ignore_where_condition_names'] ?? [];

        $this->prepareParams($where, $ignoreWhereConditionNames, $limit, $orderBy, $fields);

        //3-> 获取数据
        try {
            if ($this->useIsolatedModeInOperations) {
                $this->resetBaseQuery();
            }

            $query  = $this->baseQuery->field($fields)->order($orderBy)->limit($limit);
            $query  = $this->prepareWhere($query, $where);
            $result = $query->select();

            if ($result_as_array && $result instanceof Collection) {
                return $result->toArray();
            }

            return $result;
        } catch (DataNotFoundException|ModelNotFoundException|DbException $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
            return null;
        }
    }


    /**
     * 获取数据列表，并返回结构化结果
     * @param string|Closure|array $where
     * @param string $limit
     * @param string $orderBy
     * @param string $fields
     * @param array $options 更多可选（不常用）的参数信息，包括：
     * * 1-> ignore_where_condition_names : 忽略的where条件字段名数组（即强制要求不参与where条件的字段名）。
     * 如果包含了"__no_default__"这个特殊字符串的话，则不会添加默认的忽略条件。本属性只$where参数中的[$key=>$value]类型的元素有忽略作用。
     * * 2-> ...(其他可选参数)
     * @return array 成功返回数组['code' => 0,'msg' => '获取成功', 'count' => $count, 'data' => $result], 失败返回数组['code' => 500,'msg' => '获取失败']
     */
    public function getPagedEntitiesResult(string|Closure|array $where = [], mixed $limit = "", string $orderBy = "", string $fields = "", array $options = []): array
    {
        $ignoreWhereConditionNames = $options['ignore_where_condition_names'] ?? [];

        //1-> 整理参数
        $this->prepareParams($where, $ignoreWhereConditionNames, $limit, $orderBy, $fields);

        //2-> 获取数据
        try {
            if ($this->useIsolatedModeInOperations) {
                $this->resetBaseQuery();
            }

            $query = $this->baseQuery->field($fields)->order($orderBy);
            $query = $this->prepareWhere($query, $where);
            $list  = $query->paginate($limit)->toArray();

            return [
                'code' => 0,
                'msg' => '获取成功！',
                'count' => $list['total'],
                'data' => $list['data']
            ];
        } catch (DataNotFoundException|ModelNotFoundException|DbException  $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());

            return [
                'code' => 500,
                'msg' => '获取失败！',
                'count' => 0,
                'data' => null
            ];
        }
    }


    /**
     * 添加数据
     * @param $data array 要保存的实体数据
     * @return int | bool 成功返回新增数据的主键值; 失败返回false
     */
    public function addEntity(array $data): int|bool
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        return $this->baseQuery->insertGetId($data);
    }

    /**
     * 更新数据
     * @param $data array 要更新的实体数据
     * @return int|bool 返回影响数据的条数，没修改任何数据返回 0，失败返回false
     */
    public function updateEntity(array $data): int|bool
    {
        try {
            if ($this->useIsolatedModeInOperations) {
                $this->resetBaseQuery();
            }

            return $this->baseQuery->update($data);
        } catch (DbException $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
            return false;
        }
    }

    /**
     * 保存数据，如果主键存在数据，则更新，否则添加（本方法会自动识别主键）
     * 约定：需要数据库表中有自增的字段（推荐使用id作为名称），并且此字段要设为主键。
     * （兼容addEntity和updateEntity两个方法）
     * @param $data array 要保存的实体数据
     * @return int|bool 新增成功返回数据的主键值,失败返回false; 更新成功返回影响数据的条数,没修改任何数据返回 0.
     */
    public function saveEntity(array $data): int|bool
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        $pks = $this->baseQuery->getPk();

        $is_insert = false;
        if (empty($pks)) {
            $is_insert = true;
        }

        ////TODO:xiedali@2025/05/06 向联合主键的表中添加数据时，究竟insert还是update？逻辑还需要再优化。
        //if (is_array($pks)) {
        //    foreach ($pks as $item) {
        //        if (!isset($data, $item)) {
        //            $is_insert = true;
        //            break;
        //        }
        //    }
        //}

        if (is_string($pks)) {
            if (isset($data[$pks])) {
                //如果数据包含主键信息，并且主键值为空，亦为添加状态
                $pk_value = $data[$pks];
                if (empty($pk_value)) {
                    $is_insert = true;
                }
            } else {
                //如果数据不包含主键信息，则为添加状态
                $is_insert = true;
            }
        }

        if ($is_insert) {
            return $this->addEntity($data);
        }

        return $this->updateEntity($data);
    }

    /**
     * 保存数据，并返回结构化结果。如果主键存在数据，则更新，否则添加
     * @param array $data
     * @return array 成功返回数组['code' => 0,'msg' => '保存成功', 'data' => $result], 失败返回数组['code' => 500,'msg' => '保存失败']
     */
    public function saveEntityResult(array $data): array
    {
        $result = $this->saveEntity($data);

        if ($result) {
            return success($result, '保存成功');
        }

        return fail('保存失败');
    }

    /**
     * 批量保存数据，如果主键存在数据，则更新，否则添加
     * @param array $dataList 要保存的多个实体数据（多个数据之间用逗号分隔）；每个元素本身为一个数组。
     * @return int 添加成功的条数
     */
    public function addEntities(array ...$dataList): int
    {
        if ($this->useIsolatedModeInOperations) {
            $this->resetBaseQuery();
        }

        return $this->baseQuery->insertAll($dataList);
    }

    /**
     * 删除数据
     * @param string|Closure|array $where
     * @return int|bool 成功删除的条数; 失败返回false
     */
    public function deleteEntities(string|Closure|array $where): int|bool
    {
        //为了防止误操作，这里不允许删除所有数据
        if (empty($where)) {
            return false;
        }

        try {
            if ($this->useIsolatedModeInOperations) {
                $this->resetBaseQuery();
            }

            $query = $this->baseQuery;
            $query = $this->prepareWhere($query, $where);
            return $query->delete();
        } catch (DbException $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
            return false;
        }
    }


    /**
     * 删除数据，并返回结构化结果
     * @param string|Closure|array $where
     * @return array 成功返回数组['code' => 0,'msg' => '删除成功', 'data' => $result], 失败返回数组['code' => 500,'msg' => '删除失败']
     */
    public function deleteEntitiesResult(string|Closure|array $where): array
    {
        $result = $this->deleteEntities($where);
        if ($result) {
            return success($result, '删除成功');
        }

        return fail('删除失败');
    }


    /**
     * 对参数进行预处理
     * @param string|Closure|array $where
     * @param array $ignoreWhereConditionNames
     * @param mixed $limit
     * @param string $orderBy
     * @param string $field
     * @return void
     */
    private function prepareParams(string|Closure|array &$where, array $ignoreWhereConditionNames, mixed &$limit, string &$orderBy, string &$field): void
    {
        if (empty($limit)) {
            $limit = 0;
        }

        // 如果where参数是字符串，则直接返回；否则，解析where参数
        if (is_string($where)) {
            return;
        }

        // 如果where参数是Closure，则直接返回；否则，解析where参数
        if ($where instanceof Closure) {
            return;
        }

        //1-> 对参数进行归一处理
        if (empty($where)) {
            $where = [];
        }

        //2-> 从where中解析出limit、orderBy和fields(field)等信息
        if (isset($where['limit'])) {
            if (empty($limit)) {
                $limit = $where['limit'];
            }

            unset($where['limit']);
        }

        if (isset($where['orderby'])) {
            if (empty($orderBy)) {
                $orderBy = $where['orderby'];
            }

            unset($where['orderby']);
        }

        if (isset($where['orderBy'])) {
            if (empty($orderBy)) {
                $orderBy = $where['orderBy'];
            }

            unset($where['orderBy']);
        }

        if (isset($where['field'])) {
            if (empty($field)) {
                $field = $where['field'];
            }

            unset($where['field']);
        }

        if (isset($where['fields'])) {
            if (empty($field)) {
                $field = $where['fields'];
            }

            unset($where['fields']);
        }

        //排除掉一些非业务的过滤条件，比如page、only_refresh_time等。
        //如果包含了"__no_default__"这个特殊字符串的话，则不会有默认的忽略条件。
        if (!in_array("__no_default__", $ignoreWhereConditionNames, true)) {
            $ignoreWhereConditionNames = array_merge($ignoreWhereConditionNames, ['page', 'only_refresh_time']);
        }

        foreach ($ignoreWhereConditionNames as $item) {
            if (isset($where[$item])) {
                unset($where[$item]);
            }
        }
    }

    /**
     * 处理各种 Where条件
     * @param BaseQuery $query
     * @param string|array|Closure $where 字符串、闭包或多维数组表示的过滤条件
     * @return BaseQuery
     */
    private function prepareWhere(BaseQuery $query, string|Closure|array $where): BaseQuery
    {
        if (is_string($where)) {
            return $query->whereRaw($where);
        }

        if ($where instanceof Closure) {
            return $query->where($where);
        }

        if (!is_array($where)) {
            return $query;
        }

        //处理比如 ['id', '=', 1] 或者 ['id' , 'BETWEEN', [1, 10]] 这种简单结构的一维数组
        //判断标准：一维索引数组，并且数组第一个元素是字符串
        if (ArrayHelper::getDimensionCount($where) <= 2 &&
            ArrayHelper::isIndex($where) &&
            count($where) > 0 &&
            gettype($where[0]) === 'string') {
            $where = [$where];
        }


        $conditionOrPrefix  = self::getConditionOrPrefix();
        $conditionAndPrefix = self::getConditionAndPrefix();

        foreach ($where as $key => $value) {
            //1->如果是索引数组，则肯定多个条件全部为AND关系
            if (is_numeric($key)) {
                $query = $query->where([$value]);
            }

            //2->如果是关联数组，则需要处理OR关系
            if (is_string($key)) {
                if (StringHelper::isStartWith($key, $conditionOrPrefix)) {
                    if (ArrayHelper::getDimensionCount($value) === 1) {
                        $value = [$value];
                    }
                    $query = $query->whereOr($value);
                } else if (StringHelper::isStartWith($key, $conditionAndPrefix)) {
                    if (ArrayHelper::getDimensionCount($value) === 1) {
                        $value = [$value];
                    }
                    $query = $query->where($value);
                } else {
                    // 如果是传递的数组为 ['id' => 2] 这种形式，则继续保留这种格式
                    $query = $query->where([$key => $value]);
                }
            }
        }

        return $query;
    }
}
