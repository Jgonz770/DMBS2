<?php 

$database = MDB2::connect('mssql://cop4722:4722@teachms.cs.fiu.edu/cop4722');
if (MDB2::isError($database)) 
{
  die("cannot connect - " . $database->getMessage() . $database->getDebugInfo());
}
$database->setErrorHandling(PEAR_ERROR_DIE);
$query = $database->query('
SELECT A.pno, e1.fname, e1.lname, e2.fname, e2.lname
FROM Works_On A, Works_On B, employee e1, employee e2
WHERE A.PNO = B.PNO AND A.ESSN < B.ESSN AND e1.ssn = a.essn AND e2.ssn = b.essn
ORDER BY PNO, e1.fname, e1.lname, e2.fname, e2.lname');
while ($record = $query->fetchRow()) 
{
  print("$record[0] &nbsp;&nbsp; $record[1] $record[2] &nbsp; - &nbsp; $record[3] $record[4] <br>");
}
?>