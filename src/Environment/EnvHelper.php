<?php

namespace WanRen\Environment;


use Composer\InstalledVersions;
use WanRen\Data\JsonHelper;
use WanRen\Data\StringHelper;

/**
 *
 */
class EnvHelper
{
    /**
     * 获取Vendor中某个库的版本号
     * @param string $libraryName 第三方库的名称
     * @return string 版本号
     *
     */
    public static function getVendorLibraryVersion(string $libraryName): string
    {
        //Composer在文件InstalledVersions.php中，有提供获取已安装包信息的API，可以直接调用
        return InstalledVersions::getPrettyVersion($libraryName);
    }

    /**
     * 获取web项目的物理根路径
     * ————————————————————
     *因为当前文件属于类库文件(假定名称为a),
     *客户浏览器请求的页面(假定为b)
     *当用composer加载的时候a的时候,
     *a、b两个文件对应的物理文件,在根目录下是并列的存在的两个分支子目录.
     *因此可以通过以下逻辑获取到项目的根目录物理路径
     * @return string
     */
    public static function getPhysicalRootPath(): string
    {
        //当前文件的全物理路径文件名称
        $current_path = __FILE__;
        $current_path = realpath($current_path);

        //在客户浏览器里面,请求的页面的全物理路径文件名称
        $request_path = $_SERVER['SCRIPT_FILENAME'];
        $request_path = realpath($request_path);

        $current_path_array = explode(DIRECTORY_SEPARATOR, $current_path);
        $request_path_array = explode(DIRECTORY_SEPARATOR, $request_path);

        $current_path_length = count($current_path_array);
        $request_path_length = count($request_path_array);

        $min_length = min($current_path_length, $request_path_length);

        $root_array = [];
        for ($i = 0; $i < $min_length; $i++) {
            if ($current_path_array[$i] === $request_path_array[$i]) {
                $root_array[] = $current_path_array[$i];
            } else {
                break;
            }
        }

        $rootPath = implode(DIRECTORY_SEPARATOR, $root_array);

        /**
         * 在实际项目中,此方法有可能是被单元测试工具加载，单元测试工具又可能也在 vendor 目录下，
         * 那么此种情况，就需要根据本文件所在的目录,移除到最后一个vendor(有可能目录其他部分还包含vendor),前面剩余的部分就是根目录。
         */
        if (StringHelper::isEndWith($rootPath, DIRECTORY_SEPARATOR . "vendor")) {
            $positions     = StringHelper::getPositions($current_path, DIRECTORY_SEPARATOR . "vendor");
            $lastPosition  = 0;
            $positionCount = count($positions);

            if ($positions && $positionCount > 0) {
                $lastPosition = $positions[$positionCount - 1];
            }

            return mb_substr($current_path, 0, $lastPosition);
        } else {
            return $rootPath;
        }
    }
}
