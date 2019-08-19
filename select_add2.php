<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sannum = mysqli_real_escape_string($con, $_POST['course']);
echo "<title>Team 73 - Additional 2</title>";
$sql = "SELECT course_num as course  FROM tp_course WHERE courseID = $sannum";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
		echo "<h2>Sections of ".$row["course"]."</h2>";
	}
}

//insert query to insert form data into artist table
$sql="SELECT CONCAT(s.semester_title,' ', TIME_FORMAT(s.time, '%h:%i%p')) as section, s.sectionID, CONCAT(e.fname, ' ', e.lname) as name, ep.number as phone, ee.email_address as email, d.department_name as department
FROM tp_section as s, tp_employee as e, tp_employee_phone as ep, tp_employee_email as ee, tp_department as d, tp_course as c, tp_employee_section as es
WHERE c.courseID = $sannum and s.courseID = c.courseID and es.sectionID = s.sectionID and e.employeeID = es.employeeID and c.departmentID = d.departmentID and e.employeeID = ep.employeeID and e.employeeID = ee.employeeID
GROUP BY s.sectionID, name;";

$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Section</th><th>Instructor</th><th>Phone</th><th>Email</th><th>Department</th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" .$row["section"]."</td><td>" .$row["name"]."</td><td>" .$row["phone"]."</td><td>" .$row["email"]."</td><td>" .$row["department"]."</td></tr>";
	}
	echo "</table>";
	echo "$num_rows Rows\n";
} else { 
	echo "0 results";
}
echo "<br><br><a href='http://cgi.soic.indiana.edu/~mlightca/Team73/tp_team73.php'>Go Back</a>";
mysqli_close($con);
?>