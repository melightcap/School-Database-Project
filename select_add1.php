<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sanmajor = mysqli_real_escape_string($con, $_POST['major1']);
echo "<title>Team 73 - Additional 1</title>";
echo "<h2>Students working towards a $sanmajor</h2>";

//insert query to insert form data into artist table
$sql="SELECT CONCAT(fname, ' ', lname) as name, address, stu_number, email_address, CONCAT(parent_fname, ' ', parent_lname) as parent_name, par_number
FROM tp_scm
WHERE major_name = '$sanmajor'
group by name
UNION ALL
SELECT 'Total Students' as name, 
(SELECT count(*) from tp_student as st, tp_major as m WHERE st.studentID = m.studentID and m.major_name = '$sanmajor')
as address, 'Required Credits' as stu_number, required_credits as email_address, ' ' as parent_name,' ' as par_number
from tp_scm
where major_name = '$sanmajor'
group by name;";

$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Student Name</th><th>Address</th><th>Phone</th><th>Email</th><th></th><th>Parent Name</th><th>Parent Phone</th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		if ($row["name"] == "Total Students"){
			echo "<tr><td><b>" .$row["name"]."</b></td><td><b>" .$row["address"]."</b></td><td><b>" .$row["stu_number"]."</b></td><td><b>" .$row["email_address"]."</b></td><td></td><td><b>" .$row["parent_name"]."</b></td><td><b>" .$row["par_number"]."</b></td></tr>";
		}else{
		echo "<tr><td>" .$row["name"]."</td><td>" .$row["address"]."</td><td>" .$row["stu_number"]."</td><td>" .$row["email_address"]."</td><td></td><td>" .$row["parent_name"]."</td><td>" .$row["par_number"]."</td></tr>";
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