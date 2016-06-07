<?php
print ("<br>");
// Obtain the status of dependent from post.
$dependent = $_POST['dependent'];
if (!($dependent )) 
{
  if ($_POST['visited']) 
  {	  
	if (! $dependent) 
	{
       $dependentmsg = 'Please enter a Dependent Name';
    }
  }
 // printing the form to enter the user input
 print <<<_HTML_
 <FORM method="POST" action="{$_SERVER['PHP_SELF']}">
 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
 <font color= 'red'>$dependentmsg</font><br>
 Dependent name: <input type="text" name="dependent" size="15" value="$dependent">
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
	//$database->setErrorHandling(PEAR_ERROR_DIE);
	 {  
	  $querystring = "
	  SELECT dependent_name, employee.fname, employee.lname, manager.fname, manager.lname 
	  FROM dependent, department, employee, employee manager  
	  WHERE dependent_name = '$dependent' 
	  AND employee.ssn = dependent.essn 
	  AND (employee.ssn = mgrssn OR employee.superssn = mgrssn) 
	  AND employee.dno = manager.dno 
	  AND manager.ssn = mgrssn";
	  
	  $queryresult = mysqli_query($con, $querystring);
	  if (!$queryresult) {
    print ( "Could not successfully run query ($querystring) from DB: " . mysqli_error($con) . "<br>");
    exit;
	  }
	  print("Output for the dependent $dependent:  <br>");
	  
	  /*foreach ($queryresult as $record) 
	  {
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Dependent: $record[0] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Employee: $record[1] $record[2] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Manager: $record[3] $record[4] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; <br>");
	  }
	  
	  if (empty($queryresult)) 
	  {
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Invalid dependent name $dependent <br>");
	  } */
	  if (mysqli_num_rows($queryresult) == 0) {
    print (" &nbsp; &nbsp; &nbsp; &nbsp; Invalid dependent name $dependent <br>");
    exit;
  }

  while ($rows = mysqli_fetch_assoc($queryresult)) {
    foreach ($rows as $record) {
	
	print(" &nbsp; &nbsp; &nbsp; &nbsp; Dependent: $record[0] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Employee: $record[1] $record[2] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; Manager: $record[3] $record[4] <br>");
		
		print(" &nbsp; &nbsp; &nbsp; &nbsp; <br>");
  }

}
	 }
}

?>