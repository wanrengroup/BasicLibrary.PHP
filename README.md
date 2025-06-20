# 说明

## 项目介绍

1. 本项目是对PHP常用函数、类、扩展的封装，方便开发者使用。
2. 本项目已自动关联到PHP包管理仓库Packagist（https://packagist.org/ ），可以直接通过composer安装和更新。

## 版本说明

- 本分支(V3.2)跟master分支，并行开发，互不合并，两个分支的代码除语法差异外，大部分功能相同。
- master分支应用于ThinkORM3.x/4.x + PHP8.0环境下，
- V3.2分支应用于ThinkPHTP3.2.x + PHP7.4环境下。

## 项目文件结构配置

- 项目中使用的ThinkPHP目录，应该采用软连接的方式引入，而不是直接拷贝到项目目录下。具体操作：
    - 在命令行模式中，进入本项目的根目录，
    - 执行命令：`mklink /D "ThinkPHP" "/path/to/TP32project/ThinkPHP"`，例如：
      `mklink /D "ThinkPHP" "D:\HOME\OpenSource\ThinkPHP3.2\ThinkPHP"`
    - 然后在项目中使用`ThinkPHP`目录以及其目录下的类库、函数等即可。
    - 当向git提交代码时，只需要提交`ThinkPHP`目录（而不会提交整个ThinkPHP目录下的所有文件）即可。

## 安装与使用

```
// 安装指定版本(支持环境ThinPHP3.2.x + PHP7.4)
composer require wanren/basiclibrary.php:3.2.x-dev
```

安装完成后，在代码中引入以下代码即可使用。

## 本项目更新的方式

项目代码修改后，提交到`Gitee`后，`Packagist`会检测并自动更新（如果没有自动更新，请按照`.nogit.readme.md`
文件内的说明手动执行）。

第三方项目使用以下命令即可获得最新版本的功能：`composer update wanren/basiclibrary.php:3.2.x-dev`。
