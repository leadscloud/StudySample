<?php
/**
 * 数据库处理函数，以后转换为数据库处理类
 * 
 * @author  Ray
 * @version 1.0
 * @package main
 */

/*
数据表
CREATE TABLE IF NOT EXISTS `domain` (
  `postid` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `ip` varchar(10) NOT NULL,
  `ftphost` varchar(100) NOT NULL,
  `ftpuser` varchar(50) NOT NULL,
  `ftppass` varchar(50) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `edittime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `note` longtext,
  PRIMARY KEY (`postid`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/

/*
* 填写数据库的基本连接信息
*/
$db_host	= 'localhost';
$db_user	= 'root';
$db_pass	= '';
$db_name	= 'domain';



/**
 * 连接数据库
 * @return  bool|void 
 */
function connect() {
	global $db_host,$db_user,$db_pass;
	$conn = mysql_connect($db_host,$db_user,$db_pass,false);
	if (!$conn) {
		return throw_error(sprintf('数据库连接出错:%s', mysql_error()),E_SYS_ERROR);
	}
	return $conn;
} // end func


/**
 * 选择数据库
 * @param string $db 数据库名字
 */
function select_db($db=null,$conn) {
	if (empty($db)) return false;
	if ($conn) {
		if (!mysql_select_db($db,$conn)) {
            return throw_error(sprintf('没有找到数据库 %s !',$db),E_SYS_ERROR);
        }
		if (mysql_client_encoding($conn) != 'utf8')
			mysql_query("SET NAMES utf8;", $conn);
	}
	return true;
} // end func

/**
 * 指定函数执行SQL语句
 *
 * @param string $sql	sql语句
 * @return resource
 */
function query($sql){
	global $db_name;
	$conn = connect();
	if (!$conn) {
		return throw_error('提供的参数是不是一个有效的MySQL-Link资源。',E_SYS_ERROR);
	}
	select_db($db_name,$conn);
	if (!($result = mysql_query($sql,$conn))) {
		return throw_error(sprintf("MySQL Query 错误:%s",$sql."\r\n\t".mysql_error($conn)),E_SYS_ERROR);
	}
	return $result;
}
/**
 * 取得数据集的单条记录
 *
 * @param resource  $result
 * @param int       $mode
 * @return array
 */
function fetch($result,$mode=1){
	switch (intval($mode)) {
		case 0: $mode = MYSQL_NUM;break;
		case 1: $mode = MYSQL_ASSOC;break;
		case 2: $mode = MYSQL_BOTH;break;
	}
	return mysql_fetch_array($result,$mode);
}

/**
 * 插入数据
 *
 * @param string $table    table
 * @param array  $data     插入数据的数组，key对应列名，value对应值
 * @return int
 */
function insert($table,$data){
	$cols = array();
	$vals = array();
	foreach ($data as $col => $val) {
		$cols[] = $col;
		$vals[] = $val;
	}

	$sql = "INSERT INTO "
		 . $table
		 . ' (' . implode(', ', $cols) . ') '
		 . "VALUES ('" . implode("', '", escape($vals)) . "')";

	return query($sql);
}
/**
 * 更新数据
 *
 * @param string $table    table
 * @param array  $sets     set 数组
 * @param mixed  $where    where语句，支持数组，数组默认使用 AND 连接
 * @return int
 */
function update($table,$sets,$where=null){
	// extract and quote col names from the array keys
	$set = array();
	foreach ($sets as $col => $val) {
		$val   = escape($val);
		$set[] = $col." = '".$val."'";
	}
	$where = where($where);
	// build the statement
	$sql = "UPDATE "
		 . $table
		 . ' SET ' . implode(', ', $set)
		 . (($where) ? " WHERE {$where}" : '');

	return query($sql);
}
/**
 * 删除数据
 *
 * @param string $table
 * @param string $where
 * @return int
 */
function delete($table,$where=null){
	$where = where($where);
	// build the statement
	$sql = "DELETE FROM "
		 . $table
		 . (($where) ? " WHERE {$where}" : '');

	return query($sql);
}
/**
 * where语句组合
 *
 * @param mixed $data where语句，支持数组，数组默认使用 AND 连接
 * @return string
 */
function where($data) {
	if (empty($data)) {
		return '';
	}
	if (is_string($data)) {
		return $data;
	}
	$cond = array();
	foreach ($data as $field => $value) {
		$cond[] = "(" . $field ." = '". escape($value) . "')";
	}
	$sql = implode(' AND ', $cond);
	return $sql;
}
/**
 * 转义SQL语句,预防数据库攻击
 *
 * @param mixed $value
 * @return string
 */
function escape($value){
	// 空
	if ($value === null) return '';
	// 转义变量
	$value = envalue($value);
	//http://www.w3school.com.cn/php/func_mysql_real_escape_string.asp
	$value = mysql_real_escape_string( $value );
	return $value;
}
/**
 * 转义变量
 *
 * @param mixed $value
 * @return string
 */
function envalue($value) {
	// 空
	if ($value === null) return '';
	// 不是标量
	//标量变量是指那些包含了 integer、float、string 或 boolean的变量，而 array、object 和 resource 则不是标量。
	if (!is_scalar($value)) {
		// 是数组列表
		if (is_array($value) && !is_assoc($value)) {
			$value = implode(',', $value);
		}
		// 需要序列化
		else {
			$value = serialize($value);
		}
	}
	return $value;
}
/**
 * 检查是否存在数据库
 *
 * @param  $dbname
 * @return bool
 */
function is_database($dbname){
	$res = query("SHOW DATABASES;");
	while ($rs = fetch($res,0)) {
		if ($dbname == $rs[0]) return true;
	}
	return false;
}
/**
 * 判断数据表是否存在
 *
 * 注意表名的大小写，是有区别的
 *
 * @param string $table    表名
 * @param string $db_name    数据库名
 * @return bool
 */
function is_table($table,$db_name){
	$res = query(sprintf("SHOW TABLES FROM `%s`;", $db_name));
	while ($rs = fetch($res,0)) {
		if ($table == $rs[0]) return true;
	}
	return false;
}
/**
 * 关闭 MySQL 连接
 *
 * @return bool
 */
function close($conn){
	if (is_resource($conn)) {
		return mysql_close($conn);
	}
}