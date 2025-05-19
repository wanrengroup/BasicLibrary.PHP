<?php
/**
 * @file   : ReturnData.php
 * @time   : 15:18
 * @date   : 2025/1/22
 * @mail   : 9727005@qq.com
 * @creator: ShanDong Xiedali
 * @company: Less is more.Simple is best!
 */

namespace Wanren\Data;

/**
 * 返回数据类(用于返回json数据，格式为：array('code' => $code,'msg' => $msg, 'data' => $data))
 */
class ReturnData
{
    public int $code = 0;
    public string $msg = '';
    public mixed $data = null;

    public function __construct($code = 0, $msg = 'success', $data = null)
    {
        $this->code = $code;
        $this->msg  = $msg;
        $this->data = $data;
    }
}