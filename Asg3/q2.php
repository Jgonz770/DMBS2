<?php 

{
  $host = 'localhost:3306';
  $user='root';
  $password='';
  $database = 'company';
  $con=mysqli_connect($host, $user, $password, $database);
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MariaDB: " . mysqli_connect_error();
    exit;
  }
$query = $con->query('
SELECT A.pno, e1.fname, e1.lname, e2.fname, e2.lname
FROM Works_On A, Works_On B, employee e1, employee e2
WHERE A.PNO = B.PNO AND A.ESSN < B.ESSN AND e1.ssn = a.essn AND e2.ssn = b.essn
ORDER BY PNO, e1.fname, e1.lname, e2.fname, e2.lname');
while ($record = mysqli_fetch_assoc($query)) 
{
  print("$record[0] &nbsp;&nbsp; $record[1] $record[2] &nbsp; - &nbsp; $record[3] $record[4] <br>");
}
}
?>