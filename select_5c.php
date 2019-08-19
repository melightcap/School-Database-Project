<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sannum = mysqli_real_escape_string($con, $_POST['name']);
echo "<title>Team 73 - 5c</title>";
$sql = "SELECT CONCAT(fname, ' ', lname) as name  FROM tp_student WHERE studentID = $sannum";
$result = mysqli_query($con,$sql);
if (mysqli_num_rows($result) > 0){
	while($row = mysqli_fetch_assoc($result)) {
		echo "<h2>Courses taken by ".$row["name"]."</h2>";
	}
}

//insert query to insert form data into artist table
$sql="SELECT semester_title, start_date, course_num, letter_grade
FROM tp_sscd
WHERE studentID = $sannum
UNION
SELECT 'Total Credits' as semester_title, SUM(CASE WHEN grade_points > 0 THEN credit_hours ELSE 0 END) as start_date, 'GPA' as course_num, CAST((SUM(grade_points)/ COUNT(grade_points)) as decimal(11,3)) as letter_grade
FROM tp_sscd
WHERE studentID = $sannum
ORDER BY if(semester_title !=  'Total Credits', 0, 1), start_date;";


$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Semester</th><th>Start Date</th><th>Course</th><th>Grade</th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		if ($row["semester_title"] == 'Total Credits'){
			echo "<tr><td><b>" .$row["semester_title"]."</b></td><td><b>".$row["start_date"]."</b></td><td><b>" .$row["course_num"]."</b></td><td><b>" .$row["letter_grade"]."</b></td></tr>";
		}else{
			echo "<tr><td>" .$row["semester_title"]."</td><td>".$row["start_date"]."</td><td>" .$row["course_num"]."</td><td>" .$row["letter_grade"]."</td></tr>";
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