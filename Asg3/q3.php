<?php
print ("<br>");
$departmentnumber = $_POST['departmentnumber'];
if (!($departmentnumber )) 
{
  if ($_POST['visited']) 
  {	  
	if (! $departmentnumber) 
	{
       $departmentnumbermessage = 'Please enter a department number';
    }
  }
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$departmentnumbermessage</font><br>
 Department number: <input type="text" name="departmentnumber" size="15" value="$departmentnumber">
 <br/>
 <br>
 <INPUT type="submit" value=" Submit ">
 <INPUT type="hidden" name="visited" value="true">
 </FORM>
_HTML_;
 
}
else 
{
  require_once 'MDB2.php';
	$database = MDB2::connect('mssql://cop4722:4722@teachms.cs.fiu.edu/cop4722');
	if (MDB2::isError($database)) 
	{
		die("cannot connect - " . $database->getMessage() . $database->getDebugInfo());
	}
	$database->setErrorHandling(PEAR_ERROR_DIE);
  $querystring = "
	SELECT dname, Count(distinct pname) As NumberOfProjects, SUM(HOURS) As TotalWorkHours
    FROM department, project, works_on
    Where dnum='$departmentnumber'
    AND pno=pnumber
    AND department.dnumber=project.dnum
    Group by dname";
				  
  $queryresult = $database->queryAll($querystring);
  
  print("Output for the department number $departmentnumber:  <br>");
  
  foreach ($queryresult as $row) 
  {
    print(" &nbsp; &nbsp; &nbsp; &nbsp; Department name: $row[0] <br>");
	
	print(" &nbsp; &nbsp; &nbsp; &nbsp; Number of project: $row[1] <br>");
	
	print(" &nbsp; &nbsp; &nbsp; &nbsp; Total work hours: $row[2] <br>");
	
	print(" &nbsp; &nbsp; &nbsp; &nbsp; <br>");
  }
	
  if (empty($queryresult)) 
  {
	print(" &nbsp; &nbsp; &nbsp; &nbsp; Invalid Department Number $departmentnumber <br>");
  }
}
?>