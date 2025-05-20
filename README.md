# 说明

1. 本项目是对PHP常用函数、类、扩展的封装，方便开发者使用。
2. 本项目已自动关联到PHP包管理仓库Packagist（https://packagist.org/ ），可以直接通过composer安装和更新。

## 安装与使用

```
composer require wanren/basiclibrary.php
```

安装完成后，在代码中引入以下代码即可使用。

## 本项目更新的方式

项目代码修改后，提交到`Gitee`后，`Packagist`会检测并自动更新（如果没有自动更新，就按照`.nogit.readme.md`
文件内的说明手动执行）。

第三方项目使用以下命令即可获得最新版本的功能：`composer update wanren/basiclibrary.php`。

## 依赖库的版本说明
- topthink/think-orm 目前版本为3.0.18。不支持版本3.0.34。
