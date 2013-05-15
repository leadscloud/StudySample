<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>mysql</title>
</head>

<body>
<style type="text/css">
 table{ width:1000px;}
 form{width:1000px;}
 input{ width:80px;}
</style>
<?php
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
 
if (mysql_query("CREATE DATABASE IF NOT EXISTS domain_manage",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error();
  }
mysql_select_db("domain_manage", $con);
$sql = "CREATE TABLE IF NOT EXISTS domain 
(
userid int NOT NULL AUTO_INCREMENT, 
PRIMARY KEY(userid),

DomainName varchar(60),
Belongs varchar(20),
Nickname varchar(20),
AddDate varchar(40),
ExpirDates varchar(40),
EditDate varchar(40),
IPAddress varchar(30),
FtpAddress varchar(15),
UserName varchar(15),
Passwords varchar(20),
Notes varchar(254)
)";
$endtimes =  date('Y-m-d H:i',time());
echo "<table border='1'>
<tr>
<th>域名</th>
<th>所属人</th>
<th>昵称</th>
<th>添加日期</th>
<th>过期日期</th>

<th>FTP地址</th>
<th>FTP用户名</th>
<th>FTP密码</th>
<th>备注</th>
<th>操作</th>
</tr>";
echo'<form action="" method="post">';
echo "<td>" .'<input name="DomainName" />'. "</td>";
echo "<td>" .'<input name="Belongs" />'. "</td>";
echo "<td>" .'<input name="Nickname" />'. "</td>";
echo "<td>" .'<input name="AddDate" />'. "</td>";
echo "<td>" .'<input name="ExpirDates" />'. "</td>";
echo "<td>" .'<input name="FtpAddress" />'. "</td>";
echo "<td>" .'<input name="UserName" />'. "</td>";
echo "<td>" .'<input name="Passwords" />'. "</td>";
echo "<td>" .'<input name="Notes" />'. "</td>";
echo "<td>" .'<input type="submit" name="submit" value="Add" />'. "</td>";
echo '</form>';
echo "</table>";
$submit=isset($_POST['submit'])?$_POST['submit']:null;
$DomainName=$_POST['DomainName'];
$Belongs=$_POST['Belongs'];
$Nickname=$_POST['Nickname'];
$AddDate=$_POST['AddDate'];
$ExpirDates=$_POST['ExpirDates'];
$FtpAddress=$_POST['FtpAddress'];
$UserName=$_POST['UserName'];
$Passwords=$_POST['Passwords'];
$Notes=$_POST['Notes'];
$IPAddress = gethostbyname($DomainName);
if ($DomainName==""){
	 echo "请输入完整信息";
	}
else{
mysql_query("INSERT INTO domain (DomainName,Belongs,Nickname,AddDate,ExpirDates,EditDate,IPAddress,FtpAddress,UserName,Passwords,Notes) 
VALUES ('$DomainName','$Belongs','$Nickname','$AddDate','$ExpirDates','$endtimes','$IPAddress','$FtpAddress','$UserName','$Passwords','$Notes')");
}

$result = mysql_query("SELECT * FROM domain");
echo'<form action="" method="post">';
echo "<table border='1'>
<tr>
<th>编号</th>
<th>域名</th>
<th>所属人</th>
<th>昵称</th>
<th>添加日期</th>
<th>过期日期</th>
<th>编辑日期</th>
<th>域名IP</th>
<th>FTP地址</th>
<th>FTP用户名</th>
<th>FTP密码</th>
<th>备注</th>
<th>删除</th>
</tr>";
while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['userid'] . "</td>";
  echo "<td>" . $row['DomainName'] . "</td>";
  echo "<td>" . $row['Belongs'] . "</td>";
  echo "<td>" . $row['Nickname'] . "</td>";
  echo "<td>" . $row['AddDate'] . "</td>";
  echo "<td>" . $row['ExpirDates'] . "</td>";
  echo "<td>" . $row['EditDate'] . "</td>";
  echo "<td>" . $row['IPAddress'] . "</td>";
  echo "<td>" . $row['FtpAddress'] . "</td>";
  echo "<td>" . $row['UserName'] . "</td>";
  echo "<td>" . $row['Passwords'] . "</td>";
  echo "<td>" . $row['Notes'] . "</td>";
  echo "<td>" .'<input type="submit" name="Delete" value="Delete" />'. "</td>";
  echo "</tr>";
  }
echo "</table>";
echo '</form>';



if($_POST['submit']=='Delete'){
	$deid=$_POST['userid'];
echo $deid;
$deid = mysql_query("SELECT userid FROM domain");
mysql_query("DELETE FROM domain WHERE userid='$deid'");
}
	
mysql_query($sql,$con);
mysql_close($con);
?>
</body>
</html>
