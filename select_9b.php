<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sanmajor = mysqli_real_escape_string($con, $_POST['major']);
echo "<title>Team 73 - 9b</title>";
echo "<h2>Students who have enough credits to graduate with a $sanmajor</h2>";

//insert query to insert form data into artist table
$sql="SELECT CONCAT (st.fname, ' ', st.lname) as name FROM tp_student as st, tp_major as m 
WHERE m.studentID = st.studentID and m.major_name = '$sanmajor' and 
m.required_credits <= ( SELECT SUM(c.credit_hours) FROM tp_course as c, tp_course_details as cd, tp_section as se
WHERE se.courseID = c.courseID and cd.sectionID = se.sectionID and st.studentID = cd.studentID) GROUP BY name;";

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