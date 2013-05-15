<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>域名管理系统</title>
<style type="text/css">

body{
	width:980px;
	margin:10px auto;
	text-align:center;
	font-size:12px;
	}
table{
	width:970px;
	margin:10px auto;
	}
form{
	width:500px;
	}
form label{
	display:block;
	width:100px;
	text-align:right;
	margin-right:5px;
	float:left;

	}
form input{
	float:left;
	width:300px;
	}
.submit{
	width:80px;
	float:right;
	}

</style>
</head>
<body>
<h1>域名管理系统</h1>
<hr />
<?php
$php_file = basename(__FILE__);
$con = mysql_connect("localhost","root","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }
// Create database
if(!mysql_query("exists zyl_db" ))
	{
	mysql_query("CREATE DATABASE zyl_db",$con);
	// Create table
	mysql_select_db("zyl_db", $con);
	$sql = "CREATE TABLE Website 
	(
	ID int NOT NULL AUTO_INCREMENT, 
	PRIMARY KEY(ID),
	Website varchar(225),
	Belongs varchar(10),
	Username varchar(10),
	Nickname varchar(10),
	IP varchar(10),
	FTPurl varchar(255),
	FTPname varchar(255),
	FTPpassword varchar(255),
	Remark varchar(255),
	Dateadded date,
	Dateedited date
	)";
	
	mysql_query($sql,$con);
	mysql_select_db("zyl_db", $con);
	}
$method =isset($_GET['method'])?$_GET['method']:null;
switch($method){
	case 'new':
	echo '<form method="POST" action="" style="width:500px; height:500px; margin:20px;">
	 <p><label>域名：</label><input type="text" name="website"></input></p>
	 <p><label>所属人：</label><input type="text" name="belongs"></input></p>
	 <p><label>用户名：</label><input type="text" name="username"></input></p>
	 <p><label>昵称：</label><input type="text" name="nickname"></input></p>
	 <p><label>网站IP：</label><input type="text" name="ip"></input></p>
	 <p><label>FTP地址：</label><input type="text" name="ftpurl"></input></p>
	 <p><label>FTP用户名：</label><input type="text" name="ftpname"></input></p>
	 <p><label>FTP密码：</label><input type="text" name="ftppassword"></input></p>
	 <p><label>备注：</label><input type="text" name="remark"></input></p>
	 <p><input type="submit" class="submit" name="submit" value="添加新域名" /></p>
	 </form>';
	if(isset($_POST['submit'])){
	$sql="INSERT INTO website (Website, Belongs, Username, Nickname, IP, FTPurl, FTPname, FTPpassword, Remark)
VALUES
('$_POST[website]','$_POST[belongs]','$_POST[username]','$_POST[username]','$_POST[username]','$_POST[username]','$_POST[nickname]','$_POST[ip]','$_POST[ftpurl]','$_POST[ftpname]','$_POST[ftppassword]','$_POST[remark]')";

if (!mysql_query($sql,$con))
  {
  die('Error: ' . mysql_error());
  }
echo "域名信息添加成功 " . '<a href="' . $php_file . '">' . "返回首页" . '</a>';
	}
	break;
	case 'edit':
	$id = isset($_GET['id'])?$_GET['id']:null;
	$result = mysql_query("select * from website where ID = $id");
	while($row = mysql_fetch_array($result))
   { 
	echo '<form method="POST" action="" style="width:500px; height:500px; margin:20px;">
	 <p><label>域名：</label><input type="text" name="website" value="' .$row['Website'].'"></input></p>
	 <p><label>所属人：</label><input type="text" name="belongs"value="' .$row['Website'].'"></input></p>
	 <p><label>用户名：</label><input type="text" name="username"value="' .$row['Website'].'"></input></p>
	 <p><label>昵称：</label><input type="text" name="nickname"value="' .$row['Website'].'"></input></p>
	 <p><label>网站IP：</label><input type="text" name="ip"value="' .$row['Website'].'"></input></p>
	 <p><label>FTP地址：</label><input type="text" name="ftpurl"value="' .$row['Website'].'"></input></p>
	 <p><label>FTP用户名：</label><input type="text" name="ftpname"value="' .$row['Website'].'"></input></p>
	 <p><label>FTP密码：</label><input type="text" name="ftppassword"value="' .$row['Website'].'"></input></p>
	 <p><label>备注：</label><input type="text" name="remark"value="' .$row['Website'].'"></input></p>
	 <p><input type="submit" class="submit" name="submit" value="添加新域名" /></p>
	 </form>';

  }
	break;
	case 'delete':
	break;
	default:
	// echo table	
	$result = mysql_query("SELECT * FROM website");
	echo "<table width='980' border='1'>
		 <tr>
			<td colspan='10'>网站信息 (<a href=" . $php_file  . "?method=new" . "> 添加新域名</a> )</td>
			<td width='70'>操作</td>
		</tr>
		<tr>
			<td width='30'>ID</td>
			<td width='200'>域名</td>
			<td width='60'>所属人</td>
			<td width='60'>用户名</td>
			<td width='60'>昵称</td>
			<td width='100'>网站IP</td>
			<td width='80'>FTP信息</td>
			<td width='100'>备注</td>
			<td width='70'>添加日期</td>
			<td width='70'>编辑日期</td>
			<td>编辑/删除</td>
		</tr>
		";
	while($row = mysql_fetch_array($result))
		{
		 echo "<tr><td>" . $row['ID'] . "</td>";
		 echo "<td>" . $row['Website'] . "</td>";
		 echo "<td>" . $row['Belongs'] . "</td>";
		 echo "<td>" . $row['Username'] . "</td>";
		 echo "<td>" . $row['Nickname'] . "</td>";
		 echo "<td>" . $row['IP'] . "</td>";
		 echo "<td> 点击查看 </td>";
		 echo "<td>" . $row['Remark'] . "</td>";
		 echo "<td>" . $row['Dateadded'] . "</td>";
		 echo "<td>" . $row['Dateedited'] . "</td>";
		 echo '<td><a href="'.$php_file.'?method=edit&id=' . $row['ID']. '">编辑</a> / <a href="' .$php_file.'?method=delete&id=' . $row['ID']. '">编辑</a></td></tr>';
		 }
	echo "
		</table>";
	break;
}

mysql_close($con);
?>

</body>
</html>