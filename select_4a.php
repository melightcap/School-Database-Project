<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sannum = mysqli_real_escape_string($con, $_POST['four']);
echo "<title>Team 73 - 4a</title>";
$sql = "SELECT course_num as course  FROM tp_course WHERE courseID = $sannum";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
		echo "<h2>Students eligible to register for ".$row["course"]."</h2>";
	}
}

//insert query to insert form data into artist table
$sql="SELECT CONCAT(fname,' ',lname) as name
FROM tp_student
WHERE studentID in (
SELECT st.studentID
FROM tp_course_prereq as cp, tp_section as s, tp_course_details as cd, 
tp_student as st, tp_course as c
WHERE cp.courseID = $sannum and cp.prereqID = s.courseID and cd.sectionID = s.sectionID
and st.studentID = cd.studentID
);";

$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Student Name</th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" .$row["name"]."</td></tr>";
	}
	echo "</table>";
	echo "$num_rows Rows\n";
} else { 
	echo "0 results";
}
echo "<br><br><a href='http://cgi.soic.indiana.edu/~mlightca/Team73/tp_team73.php'>Go Back</a>";
mysqli_close($con);
?>