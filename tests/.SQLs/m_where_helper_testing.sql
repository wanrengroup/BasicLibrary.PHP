-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.7.26 - MySQL Community Server (GPL)
-- 服务器操作系统:                      Win64
-- HeidiSQL 版本:                  11.0.0.5919
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出  表 my_wr_basic_library.m_where_helper_testing 结构
CREATE TABLE IF NOT EXISTS `m_where_helper_testing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `grade` int(11) DEFAULT NULL COMMENT '年级',
  `mobile` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=DYNAMIC COMMENT='本地测试WhereHelper的表';

-- 正在导出表  my_wr_basic_library.m_where_helper_testing 的数据：~0 rows (大约)
DELETE FROM `m_where_helper_testing`;
/*!40000 ALTER TABLE `m_where_helper_testing` DISABLE KEYS */;
INSERT INTO `m_where_helper_testing` (`id`, `name`, `alias`, `grade`, `mobile`, `create_date`) VALUES
	(1, '张三', '张老师', 2, '13361108012', '2025-05-02'),
	(2, '李四', '李斯', 2, '13361108013', NULL),
	(3, '王五', '老王', 3, '13361108014', '2025-05-01'),
	(4, '赵六', '赵中国', 3, '13361108015', NULL),
	(5, '何七', '何仙姑', 2, '13361108016', '2025-05-04'),
	(6, '张八', '大张八', 4, '13361108017', '2025-05-05'),
	(7, '老张', '老张A', 3, '13361108018', NULL),
	(8, '张九龄', '诗人老张', 4, '13361108019', '2025-05-03'),
	(9, '张十方', '张十方', 2, '13361108020', NULL);
/*!40000 ALTER TABLE `m_where_helper_testing` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
