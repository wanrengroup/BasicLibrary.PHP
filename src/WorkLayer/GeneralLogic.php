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

/**
 * 通用的逻辑层类
 * （如果不想创建具体的Model和Logic，就可以使用本通用Logic类；
 * 使用的时候，只要给构造函数传入不带前缀的表名即可，例如：
 * $logic  = new GeneralLogic("no_publish_c");）
 */
class GeneralLogic extends AbstractLogic
{
    /**
     * 构造函数
     * @param string $modelName
     * @param array $connectionNameOrOptions
     */
    public function __construct(string $modelName, array $connectionNameOrOptions = [])
    {
        parent::__construct($modelName, $connectionNameOrOptions);
    }


    /**
     * 可以加入更多的逻辑方法
     */
}