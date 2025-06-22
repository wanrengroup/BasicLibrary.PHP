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

use Think\Exception;
use Think\Model;
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
 * 2.5->OR关联多维数组类型："__or__"=>[['id','=',5],['name','like','%大%']] （各个元素之间是OR关系。）
 * 2.6->V3.2版本仅支持一组 __or__ 或者 __and__ 作为条件的分隔符。
 *
 * 3->OR条件的更多说明：
 * 由于TP以及后来的ThinkORM的变化，建议进行OR条件的查询时，在保证不被SQL注入的情况下，使用字符串的形式进行。
 */
abstract class AbstractLogic
{
    private Model $model;
    private static string $conditionOrPrefix = "";
    private static string $conditionAndPrefix = "";

    /**
     * 构造函数，主要作用是实例化模型对象，传入的选填的参数为(不带前缀的)数据库表名或模型对象。
     * 创建模型对象时：如果派生类的类名称，可以跟数据库表对应，就可以省略传入表名。
     * @param string|array $connectionNameOrOptions 数据库连接名称或更多配置项（如果不传，则使用默认的数据库连接；如果传入字符串，则表示选用指定的数据库连接；如果传入数组，则表示配置项（在配置项中可以通过键名connectionName或connection指定数据库连接名称）
     * @param string $modelName 数据库表名或模型对象；如果使用默认的数据库表名，此参数也可以传入true，则使用隔离模式（此时自动忽略第二个参数）。
     * （共享模式下，前次动作对模型的操作会影响到下次的动作。比如：
     * 连续getEntity多次的时候，如果没有使用隔离模式，ThinkORM会在第后一次调用时where条件的时候叠加上前一次的where条件；让开发者感觉“莫名其妙”。
     * 因此这个时候就需要使用隔离模式。）
     * =>特别注意：如果创建了一个logic实例，多次调用的时候，也可以粗放地将此参数设置为true，这样使用隔离的模式，每次调用logic都是“全新”的。
     */
    public function __construct(string $modelName = "", $connectionNameOrOptions = "")
    {
        $this->setModelDetails($modelName, $connectionNameOrOptions);
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
     * @param string $modelName
     * @param string|array $connectionOrOptions
     * @return void
     */
    private function setModelDetails(string $modelName = "", $connectionOrOptions = ""): void
    {
        //1-> 确定模型对象的名字
        if (is_string($modelName) && empty($modelName)) {
            $modelName = str_replace('\\', '/', static::class);
            $modelName = basename($modelName);
            $modelName = str_replace('Logic', '', $modelName);
        }

        //2-> 确定数据库连接配置
        $connection = "";
        $prefix     = "";
        if (is_string($connectionOrOptions)) {
            $connection = $connectionOrOptions;
        }

        if (is_array($connectionOrOptions)) {
            //目前只支持从$options中获取数据库连接配置
            $connection = $connectionOrOptions['connection'] ?? $connectionOrOptions['connectionName'] ?? '';
            $prefix     = $connectionOrOptions['prefix'] ?? $connectionOrOptions['tablePrefix'] ?? '';

            // 尝试从connection中获取数据库表前缀
            $connectionArray = [];
            if (is_string($connection)) {
                $connectionArray = C($connection);
            }
            if (empty($prefix) && is_array($connectionArray) && !empty($connectionArray)) {
                $prefix = $connectionArray['DB_PREFIX'] ?? '';
            }
        }

        //3-> 创建模型对象
        $this->model = new Model($modelName, $prefix, $connection);
    }

    /**
     * 获取最后一条SQL语句
     * @return string
     */
    public function getLastSql(): string
    {
        return $this->model->getLastSql();
    }

    /**
     * 获取单条数据
     * @param mixed $where 具体类型可以为：string|Closure|array
     * @param string $orderBy
     * @param string $fields
     * @return array|mixed|null
     */
    public function getEntity($where, string $orderBy = "", string $fields = "")
    {
        try {
            $query = $this->model->field($fields)->order($orderBy);
            $query = $this->prepareWhere($query, $where);
            return $query->find();
        } catch (Exception $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
            return null;
        }
    }

    /**
     * 获取不包含数据（数据为0或“”），只包含数据结构的空实体。
     * （因为TP提供的方法无法获取到字段的数据类型，因此除了主键默认为数字0之外，其他字段都默认为空字符串）
     * @return array
     */
    public function getEmptyEntity(): array
    {
        $fields = $this->model->getDbFields();
        $pk     = $this->model->getPk();

        $entity = [];
        foreach ($fields as $value) {
            //默认字段类型为字符串
            $entity[$value] = "";

            //如果是主键，则默认值设为0
            if ($value === $pk) {
                $entity[$value] = 0;
            }
        }

        return $entity;
    }

    /**
     * 获取数据条数
     * @param string|array $where
     * @param string $field
     * @return int|null
     */
    public function getEntityCount($where = [], string $field = ""): ?int
    {
        if (empty($field)) {
            $field = "*";
        }

        $query = $this->getModel();
        $query = $this->prepareWhere($query, $where);
        return $query->count($field);
    }

    /**
     * 获取数据合计值
     * @param string $field
     * @param string|array $where
     * @return float
     */
    public function getEntitySum(string $field = "", $where = []): float
    {
        $query = $this->getModel();
        $query = $this->prepareWhere($query, $where);
        return $query->sum($field);
    }

    /**
     * 获取数据平均值
     * @param string $field
     * @param string|array $where
     * @return float
     */
    public function getEntityAvg(string $field, $where = []): float
    {
        $query = $this->getModel();
        $query = $this->prepareWhere($query, $where);
        return $query->avg($field);
    }

    /**
     * 获取数据最大值
     * @param string $field
     * @param string|array $where
     * @return float
     */
    public function getEntityMax(string $field, $where = []): float
    {
        $query = $this->getModel();
        $query = $this->prepareWhere($query, $where);
        return $query->max($field);
    }

    /**
     * 获取数据最小值
     * @param string $field
     * @param string|array $where
     * @return float
     */
    public function getEntityMin(string $field, $where = []): float
    {
        $query = $this->getModel();
        $query = $this->prepareWhere($query, $where);
        return $query->min($field);
    }

    /**
     * 获取数据列表
     * @param string|array $where
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
    public function getEntities($where = [], string $limit = "", string $orderBy = "", string $fields = "", array $options = [])
    {
        $result_as_array           = $options['result_as_array'] ?? true;
        $ignoreWhereConditionNames = $options['ignore_where_condition_names'] ?? [];

        $this->prepareParams($where, $ignoreWhereConditionNames, $limit, $orderBy, $fields);

        //3-> 获取数据
        try {
            $query = $this->getModel()->field($fields)->order($orderBy)->limit($limit);
            $query = $this->prepareWhere($query, $where);
            return $query->select();
        } catch (Exception $e) {
            LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
            return null;
        }
    }
    //
    //
    ///**
    // * 获取数据列表，并返回结构化结果
    // * @param string|Closure|array $where
    // * @param string $limit
    // * @param string $orderBy
    // * @param string $fields
    // * @param array $options 更多可选（不常用）的参数信息，包括：
    // * * 1-> ignore_where_condition_names : 忽略的where条件字段名数组（即强制要求不参与where条件的字段名）。
    // * 如果包含了"__no_default__"这个特殊字符串的话，则不会添加默认的忽略条件。本属性只$where参数中的[$key=>$value]类型的元素有忽略作用。
    // * * 2-> ...(其他可选参数)
    // * @return array 成功返回数组['code' => 0,'msg' => '获取成功', 'count' => $count, 'data' => $result], 失败返回数组['code' => 500,'msg' => '获取失败']
    // */
    //public function getPagedEntitiesResult(string|Closure|array $where = [], mixed $limit = "", string $orderBy = "", string $fields = "", array $options = []): array
    //{
    //    $ignoreWhereConditionNames = $options['ignore_where_condition_names'] ?? [];
    //
    //    //1-> 整理参数
    //    $this->prepareParams($where, $ignoreWhereConditionNames, $limit, $orderBy, $fields);
    //
    //    //2-> 获取数据
    //    try {
    //        if ($this->useIsolatedModeInOperations) {
    //            $this->resetBaseQuery();
    //        }
    //
    //        $query = $this->baseQuery->field($fields)->order($orderBy);
    //        $query = $this->prepareWhere($query, $where);
    //        $list  = $query->paginate($limit)->toArray();
    //
    //        return [
    //            'code' => 0,
    //            'msg' => '获取成功！',
    //            'count' => $list['total'],
    //            'data' => $list['data']
    //        ];
    //    } catch (DataNotFoundException|ModelNotFoundException|DbException  $e) {
    //        LoggerHelper::error($e->getMessage(), $e->getTraceAsString());
    //
    //        return [
    //            'code' => 500,
    //            'msg' => '获取失败！',
    //            'count' => 0,
    //            'data' => null
    //        ];
    //    }
    //}
    //
    //
    /**
     * 添加数据
     * @param $data array 要保存的实体数据
     * @return int | bool 成功返回新增数据的主键值; 失败返回false
     */
    public function addEntity(array $data)
    {
        return $this->model->add($data);
    }

    /**
     * 更新数据
     * @param $data array 要更新的实体数据
     * @return int|bool 返回影响数据的条数，没修改任何数据返回 0，失败返回false
     */
    public function updateEntity(array $data)
    {
        return $this->model->save($data);
    }

    /**
     * 保存数据，如果主键存在数据，则更新，否则添加（本方法会自动识别主键）
     * 约定：需要数据库表中有自增的字段（推荐使用id作为名称），并且此字段要设为主键。
     * （兼容addEntity和updateEntity两个方法）
     * @param $data array 要保存的实体数据
     * @return int|bool 新增成功返回数据的主键值,失败返回false; 更新成功返回影响数据的条数,没修改任何数据返回 0.
     */
    public function saveEntity(array $data)
    {
        $pks = $this->model->getPk();

        $is_insert = false;
        if (empty($pks)) {
            $is_insert = true;
        }

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
    //
    ///**
    // * 保存数据，并返回结构化结果。如果主键存在数据，则更新，否则添加
    // * @param array $data
    // * @return array 成功返回数组['code' => 0,'msg' => '保存成功', 'data' => $result], 失败返回数组['code' => 500,'msg' => '保存失败']
    // */
    //public function saveEntityResult(array $data): array
    //{
    //    $result = $this->saveEntity($data);
    //
    //    if ($result) {
    //        return success($result, '保存成功');
    //    }
    //
    //    return fail('保存失败');
    //}
    //
    /**
     * 批量保存数据，如果主键存在数据，则更新，否则添加
     * @param array $dataList 要保存的多个实体数据（多个数据之间用逗号分隔）；每个元素本身为一个数组。
     * @return int 返回影响数据的条数
     */
    public function addEntities(array ...$dataList): int
    {
        //由于系统提供的批量添加方法addALL()，返回的结果不准确（返回的本次插入的第一条的主键值），所以这里自己实现批量添加
        //return $this->getModel()->addAll($dataList);

        $rowCountEffected = 0;
        foreach ($dataList as $data) {
            $currentResult = $this->addEntity($data);
            if ($currentResult) {
                $rowCountEffected++;
            }
        }

        return $rowCountEffected;
    }

    /**
     * 删除数据
     * @param string|array $where
     * @return int|bool 成功删除的条数; 失败返回false
     */
    public function deleteEntities($where)
    {
        //为了防止误操作，这里不允许删除所有数据
        if (empty($where)) {
            return false;
        }

        $query = $this->getModel();
        $query = $this->prepareWhere($query, $where);
        return $query->delete();
    }
    //
    //
    ///**
    // * 删除数据，并返回结构化结果
    // * @param string|Closure|array $where
    // * @return array 成功返回数组['code' => 0,'msg' => '删除成功', 'data' => $result], 失败返回数组['code' => 500,'msg' => '删除失败']
    // */
    //public function deleteEntitiesResult(string|Closure|array $where): array
    //{
    //    $result = $this->deleteEntities($where);
    //    if ($result) {
    //        return success($result, '删除成功');
    //    }
    //
    //    return fail('删除失败');
    //}


    /**
     * 对参数进行预处理
     * @param string|array $where
     * @param array $ignoreWhereConditionNames
     * @param mixed $limit
     * @param string $orderBy
     * @param string $field
     * @return void
     */
    private function prepareParams(&$where, array $ignoreWhereConditionNames, string &$limit, string &$orderBy, string &$field): void
    {
        if (empty($limit)) {
            $limit = 0;
        }

        // 如果where参数是字符串，则直接返回；否则，解析where参数
        if (is_string($where)) {
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

    private function convertConditionsFormat(array $value): array
    {
        $arrayDimension = ArrayHelper::getDimensionCount($value);
        $isIndex        = ArrayHelper::isIndex($value);
        $arrayItemCount = count($value);

        //要判定是 类型：['mobile', 'like', 'thinkphp%'] 这种形式的数组
        //还是 类型：[['mobile', 'like', 'thinkphp%'], ['email', 'like', 'thinkphp%']] 这种形式的数组

        if ($arrayDimension >= 2 && $isIndex && $arrayItemCount > 0 && is_array($value[0])) {
            $map = [];
            foreach ($value as $item) {
                $map[] = $this->convertConditionFormat($item);
            }

            return $map;
        } else {
            return $this->convertConditionFormat($value);
        }
    }

    /**
     * 将['mobile', 'like', 'thinkphp%'] 这种形式条件数组转换为 ['mobile' => ['like', 'thinkphp%']] 这种形式的条件数组
     * @param array $value
     * @return array
     */
    private function convertConditionFormat(array $value): array
    {
        $map = [];

        /** @noinspection all */
        if (is_array($value) && count($value) >= 2) {
            $realKey   = $value[0];
            $realValue = [];
            foreach ($value as $index => $item) {
                if ($index > 0) {
                    $realValue[] = $item;
                }
            }
            $map[$realKey] = $realValue;
        }
        return $map;
    }

    /**
     * 处理各种 Where条件
     * @param Model $query
     * @param string|array $where 字符串、闭包或多维数组表示的过滤条件
     * @return Model
     */
    private function prepareWhere(Model $query, $where): Model
    {
        if (is_string($where)) {
            return $query->where($where);
        }

        if (!is_array($where)) {
            return $query;
        }

        //处理比如 ['id', '=', 1] 或者 ['id' , 'BETWEEN', [1, 10]] 这种简单结构的一维数组
        //判断标准：一维索引数组，并且数组第一个元素是字符串
        if (ArrayHelper::getDimensionCount($where) <= 2 &&
            ArrayHelper::isIndex($where) &&
            count($where) > 0 && gettype($where[0]) === 'string') {
            //转成标准的where条件数组（每个条件为where数组的一个元素），交由后面的逻辑统一处理
            $where = [$where];
        }

        $conditionOrPrefix  = self::getConditionOrPrefix();
        $conditionAndPrefix = self::getConditionAndPrefix();

        foreach ($where as $key => $value) {
            //1->如果是索引数组，则肯定多个条件全部为AND关系
            // 对 ['mobile', 'like', 'thinkphp%'] 这种形式array类型的value进行解析处理
            if (is_numeric($key)) {
                $map   = $this->convertConditionsFormat($value);
                $query = $query->where($map);
            }

            //2->如果是关联数组，则需要处理OR关系
            if (is_string($key)) {
                if (StringHelper::isStartWith($key, $conditionOrPrefix)) {
                    if (ArrayHelper::getDimensionCount($value) === 1) {
                        $value = [$value];
                    }

                    $condition           = $this->convertConditionsFormat($value);
                    $condition['_logic'] = 'or';
                    $map['_complex']     = $condition;

                    $query = $query->where($map);

                } else if (StringHelper::isStartWith($key, $conditionAndPrefix)) {
                    if (ArrayHelper::getDimensionCount($value) === 1) {
                        $value = [$value];
                    }

                    $condition           = $this->convertConditionsFormat($value);
                    $condition['_logic'] = 'and';
                    $map['_complex']     = $condition;

                    $query = $query->where($map);

                } else {
                    // 如果是传递的数组为 ['id' => 2] 这种形式，则继续保留这种格式
                    $query = $query->where([$key => $value]);
                }
            }
        }

        return $query;
    }
}
