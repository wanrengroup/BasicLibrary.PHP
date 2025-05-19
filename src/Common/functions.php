<?php
/**
 * 返回操作状态
 * @param array $data
 * @param string $msg
 * @return array
 */
if (!function_exists('returnStatus')) {
    function returnStatus(bool|int $status, string $msg = '', array $data = []): array
    {
        if (is_bool($status)) {
            if ($status) {
                return success($data, $msg);
            } else {
                return fail($msg, 500, $data);
            }
        }

        if (is_int($status)) {
            if ($status === 0) {
                return success($data, $msg);
            } else {
                return fail($msg, $status, $data);
            }
        }

        return success($data, $msg);
    }
}

/**
 * 返回成功
 * @param mixed $data
 * @param string $msg
 * @return array
 */
if (!function_exists('success')) {
    function success(mixed $data = [], string $msg = '', int $code = 0): array
    {
        return [
            'code' => $code,
            'msg' => !empty($msg) ? $msg : 'success',
            'data' => !empty($data) ? $data : [],
        ];
    }
}

/**
 * 返回失败
 * @param string $msg
 * @param int $code
 * @param array $data
 * @return array
 */
if (!function_exists('fail')) {
    function fail(string $msg = '', int $code = 500, array $data = []): array
    {
        return [
            'code' => $code,
            'msg' => !empty($msg) ? $msg : 'error',
            'data' => !empty($data) ? $data : [],
        ];
    }
}