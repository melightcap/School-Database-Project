<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sanadvise = mysqli_real_escape_string($con, $_POST['advisor']);
echo "<title>Team 73 - 7a</title>";
$sql = "SELECT CONCAT(fname, ' ', lname) as name  FROM tp_employee WHERE employeeID = $sanadvise";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
		echo "<h2>Students advised by ".$row["name"]."</h2>";
	}
}

//insert query to insert form data into artist table
$sql="SELECT CONCAT(s.fname, ' ', s.lname) as name, m.major_name as major
FROM tp_student as s, tp_advise as a, tp_major as m
WHERE s.studentID = a.studentID and a.employeeID = $sanadvise and m.studentID = s.studentID
ORDER BY name ASC;";

$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Student Name</th><th>Major</th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" .$row["name"]."</td><td>" .$row["major"]."</td></tr>";
	}
	echo "</table>";
	echo "$num_rows Rows\n";
} else { 
	echo "0 results";
}
echo "<br><br><a href='http://cgi.soic.indiana.edu/~mlightca/Team73/tp_team73.php'>Go Back</a>";
mysqli_close($con);
?>