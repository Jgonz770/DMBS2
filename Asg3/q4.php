<?php

print ("<br>");
$departmentname = $_POST['departmentname'];
if (!($departmentname )) 
{
  if ($_POST['visited']) 
  {	  
    if (! $departmentname) 
	{
       $departmentnamemessage = 'Please complete department name for the search';
    }
	
  }
 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$departmentnamemessage</font><br>
 Department name: <input type="text" name="departmentname" size="15" value="$departmentname">
 <br>
 <p>
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
  
  //Store the query for looking up the department name in querydepartmentname
  $querydepartmentname = "
					SELECT dname
					FROM department
					WHERE dname = '$departmentname';";
  //Store the query for looking up the first name and last name in queryfirstandlastname					
  $queryfirstandlastname = "
					SELECT fname, lname
					FROM department dptA, department dptB, employee
					WHERE dptA.dname = '$departmentname'
					AND dptA.dnumber = dptB.dnumber
					AND ssn = dptA.mgrssn;";
   
  //Store the query for looking up the number of dependents as querydependents  
  $querydependents = "	    
					SELECT COUNT(dependent_name) AS NumberOfDependents
					FROM dependent, department, employee
					WHERE dname = '$departmentname'
					AND ssn = mgrssn
					AND essn = ssn;";
					
  $departmentnametable = $con->query($querydepartmentname);
  
  $firstandlastnametable = $con->query($queryfirstandlastname);
  
  $dependentstable = $con->query($querydependents);
  
  print("Output for the department name $departmentname:<br>");
  
  if($departmentname)
	{
		while (($departmentnamerecord = mysqli_fetch_assoc($departmentnametable)) 
		&& ($namerecord = mysqli_fetch_assoc($firstandlastnametable)) 
		&& ($dependentsrecord = mysqli_fetch_assoc($dependentstable))) 
		{
			$finaltable .=  
			"&nbsp; &nbsp; &nbsp; &nbsp; Department: $departmentnamerecord[0] <br>
			&nbsp; &nbsp; &nbsp; &nbsp; Manager: $namerecord[0] $namerecord[1] <br> 
			&nbsp; &nbsp; &nbsp; &nbsp; Dependents: $dependentsrecord[0] <br><br>";
		}
	}
  if (!empty($finaltable)) 
    {	
		print $finaltable;	
    }
  else
    {
		print("&nbsp; &nbsp; &nbsp; &nbsp; Invalid Department Name $departmentname");
	}
	
}
?>