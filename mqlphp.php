<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>无标题文档</title>
</head>
<body>
<?php
$con = mysql_connect("localhost","root","");
/*if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

if (mysql_query("CREATE DATABASE my_db",$con))
  {
  echo "Database created";
  }
else
  {
  echo "Error creating database: " . mysql_error()."<br/>";
  }
  */
mysql_select_db("my_db", $con);
$sql="CREATE TABLE table_domain(
    Domain_name varchar(20) NOT NULL,
	PRIMARY KEY(Dominname),
	Domaino_wner varchar(10),
	Username varchar(10),
	Add_date varchar(10),
	Edit_date varchar(10),
	Remark varchar(20),
	Ip varchar(20),
	Ftp_address varchar(20),
	Ftp_username varchar(10),
	ftp_password varchar(20)
)";
mysql_query($sql,$con);
mysql_select_db("my_db", $con);

mysql_query("INSERT INTO table_domain (Domain_name, Domaino_wner,Username,Add_date,Edit_date,Remark,Ip,Ftp_address,ftp_password)
VALUES ('rocksplant', '', 'rockplant','2012-05-15','','','',
'','rocksplant','12345')");
$result = mysql_query("SELECT * FROM table_domain")or die(mysql_error());

while($row=mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td>" . $row['Domin_name'] . "</td>";
  echo "<td>" . $row['Domaino_wner'] . "</td>";
  echo "<td>" . $row['Username'] . "</td>";
  echo "<td>" . $row['Add_date'] . "</td>";
  echo "<td>" . $row['Edit_date'] . "</td>";
  echo "<td>" . $row['Remark'] . "</td>";
  echo "<td>" . $row['Ip']. "</td>";
  echo "<td>" . $row['Ftp_address'] . "</td>";
  echo "<td>" . $row['Ftp_username'] . "</td>";
  echo "<td>" . $row['ftp_password'] . "</td>";
  echo "</tr>";
  }
mysql_close($con);

?>
</body>
</html>