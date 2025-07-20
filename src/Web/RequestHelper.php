<?php
/**
 * @file   : RequestHelper.php
 * @time   : 08:44
 * @date   : 2025/5/4
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Web;

use think\facade\Request;
use WanRen\Data\JsonHelper;

class RequestHelper
{
    //public static function get($key, $default = null)
    //{
    //    return isset($_GET[$key])? $_GET[$key] : $default;
    //}
    //
    //public static function post($key, $default = null)
    //{
    //    return isset($_POST[$key])? $_POST[$key] : $default;
    //}
    //
    //public static function request($key, $default = null)
    //{
    //    return isset($_REQUEST[$key])? $_REQUEST[$key] : $default;
    //}
    //
    //public static function server($key, $default = null)
    //{
    //    return isset($_SERVER[$key])? $_SERVER[$key] : $default;
    //}

    //public static function getParam($key, $default = null)
    //{
    //    return Request::param($key) ?: $default;
    //}

    /**
     * 从获取请求参数的值，优先级：$_REQUEST > php://input > 默认值
     * （如果通过php://input方式获取参数，那么传递的参数必须是json格式）
     * @param string $key
     * @param string $default_value
     * @return mixed|string
     */
    public function getParam(string $key, string $default_value = "")
    {
        $value = $this->$_REQUEST[$key] ?? "";
        if ($value === "") {
            $inputString = file_get_contents("php://input");
            $inputArray  = JsonHelper::string2Array($inputString);
            $value       = $inputArray[$key] ?? "";
        }

        if ($value === "") {
            $value = $default_value;
        }
        return $value;
    }
}