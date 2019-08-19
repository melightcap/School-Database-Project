<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}
$sannum = mysqli_real_escape_string($con, $_POST['section']);
echo "<title>Team 73 - 1b</title>";
$sql = "SELECT CONCAT(c.course_num,' ',TIME_FORMAT(s.time, '%h:%i%p')) as course  FROM tp_section as s, tp_course as c WHERE s.courseID = c.courseID and s.sectionID = $sannum";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
		echo "<h2>Students in ".$row["course"]."</h2>";
	}
}

//insert query to insert form data into artist table
$sql="SELECT CONCAT(st.lname, ', ', st.fname) as name, ' ' as average
FROM tp_course_details as cd, tp_student as st
WHERE st.studentID = cd.studentID and st.studentID in (
SELECT st.studentID
FROM tp_student as st
WHERE cd.sectionID = $sannum
)
UNION
SELECT 'Class GPA' as name, CAST((AVG(cd.grade_points)) as decimal(11,3)) as average
from tp_course_details as cd, tp_student as st
WHERE st.studentID = cd.studentID and st.studentID in (
SELECT st.studentID
FROM tp_student as st
WHERE cd.sectionID = $sannum
)
GROUP BY name;";


$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Student Name</th><th></th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		if ($row["name"] == "Class GPA"){
			echo "<tr><td><b>" .$row["name"]."</b></td><td><b>" .$row["average"]."</b></td></tr>";
		}else{
		echo "<tr><td>" .$row["name"]."</td><td>" .$row["average"]."</td></tr>";
		}
	}
	echo "</table>";
	echo "$num_rows Rows\n";
} else { 
	echo "0 results";
}

echo "<br><br><a href='http://cgi.soic.indiana.edu/~mlightca/Team73/tp_team73.php'>Go Back</a>";
mysqli_close($con);
?>