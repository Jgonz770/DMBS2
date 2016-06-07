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
  $host = 'localhost:3306';
  $user='root';
  $password='';
  $database = 'company';
  $con=mysqli_connect($host, $user, $password, $database);
  if (mysqli_connect_errno()) {
    echo "Failed to connect to MariaDB: " . mysqli_connect_error();
    exit;
  }
  $querystring = "
	SELECT dname, Count(distinct pname) As NumberOfProjects, SUM(HOURS) As TotalWorkHours
    FROM department, project, works_on
    Where dnum='$departmentnumber'
    AND pno=pnumber
    AND department.dnumber=project.dnum
    Group by dname";
				  
  $queryresult = mysqli_query($con, $querystring);
  
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