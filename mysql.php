<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>域名管理系统</title>
</head>
<body>
<form action=" " method="post">
域名: <input type="text" name="domainname" /><br/>
域名所属者: <input type="text" name="domainowener" /><br/>
用户名：<input type="text" name="username" /><br/>
添加时间: <input type="text" name="addtime" /><br/>
最近修改时间：<input type="text" name="editortime" /><br/>
备注：<input type="text" name="remark" /><br/>
IP: <input type="text" name="ip" /><br/>
ftp地址：<input type="text" name="ftpaddress" /><br/>
ftp用户名：<input type="text" name="ftpusername" /><br/>
ftp密码：<input type="text" name="ftppassword" /><br/>
<input type="submit" name="submit"/><br/>
</form>
<?php
$submit=isset($_POST['submit'])?$_POST['submit']:null;
$domainname=isset($_POST['domainname'])?$_POST['domainname']:null;
$owner=isset($_POST['domainowener'])?$_POST['domainowener']:null;
$username=isset($_POST['username'])?$_POST['username']:null;
$additime=isset($_POST['addtime'])?$_POST['addtime']:null;
$edittime=isset($_POST['editortime'])?$_POST['editortime']:null;
$ip=isset($_POST['ip'])?$_POST['ip']:null;
$remark=isset($_POST['remark'])?$_POST['remark']:null;
$ftpaddress=isset($_POST['ftpaddress'])?$_POST['ftpaddress']:null;
$ftpusername=isset($_POST['ftpusername'])?$_POST['ftpusername']:null;
$ftppassword=isset($_POST['ftppassword'])?$_POST['ftppassword']:null;
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
if (mysql_query("CREATE DATABASE my_db",$con))
  {
  echo "Database created";
  }
mysql_select_db("my_db", $con);
$sql = "CREATE TABLE domain 
(
domainID int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(domainID),
domainname varchar(20),
domainowener varchar(15),
username varchar(10),
addtime varchar(20),
editortime varchar(20),
ip  varchar(255),
remark varchar(20),
ftpaddress varchar(20),
ftpusername varchar(20),
ftppassword varchar(20)
)";
mysql_query($sql,$con);
mysql_select_db("my_db", $con);
$sql_new=mysql_query("INSERT INTO domain 
(domainname, domainowener, username,addtime,editortime,ip,remark,
ftpaddress,ftpusername,ftppassword)  VALUES ($domainname, $owner, $username,$additime,$edittime,$ip,$remark,$ftpaddress,
$ftpusername,$ftppassword)");
if (!mysql_query($sql_new,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "1 record added";
$result = mysql_query("SELECT * FROM domain");
echo "<table border='1'>
<tr>
<th>domain</th>
<th>domainowener</th>
<th>username</th>
<th>addtime</th>
<th>editortime</th>
<th>ip</th>
<th>remark</th>
<th>ftpaddress</th>
<th>ftpusername</th>
<th>ftppassword</th>
</tr>";
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['domain'] . "</td>";
  echo "<td>" . $row['domainowener']. "</td>";
  echo "<td>" . $row['username'] . "</td>";
  echo "<td>" . $row['addtime']. "</td>";
  echo "<td>" . $row['editortime'] . "</td>";
  echo "<td>" . $row['ip']. "</td>";
  echo "<td>" . $row['remark'] . "</td>";
  echo "<td>" . $row['ftpaddress']. "</td>";
  echo "<td>" . $row['ftpusername'] . "</td>";
  echo "<td>" . $row['ftppassword']. "</td>";
  echo "</tr>";
  }
echo "</table>";
?>
</body>
</html>