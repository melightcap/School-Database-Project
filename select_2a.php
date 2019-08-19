<?php
$con=mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
//Check connection
if (mysqli_connect_errno())
{echo nl2br("Failed to connect to MySQL:" . mysqli_connect_error() . "\n");}

$sanfeature = mysqli_real_escape_string($con, $_POST['feature']);
echo "<title>Team 73 - 2a</title>";
echo "<h2>Classrooms with $sanfeature</h2>";

//insert query to insert form data into artist table
$sql="SELECT CONCAT (b.building_name, ' ', r.room_num) as room
FROM tp_building as b, tp_room as r, tp_room_feature as rf
WHERE b.buildingID = r.buildingID and r.roomID = rf.roomID and rf.feature = '$sanfeature';";

$result = mysqli_query($con,$sql);
$num_rows = mysqli_num_rows($result);
//check for error on insert
if (mysqli_num_rows($result) > 0) {
	echo "<table border = '1'><tr><th>Classrooms</th></tr>";
	while($row = mysqli_fetch_assoc($result)) {
		echo "<tr><td>" .$row["room"]."</td></tr>";
	}
	echo "</table>";
	echo "$num_rows Rows\n";
} else { 
	echo "0 results";
}
echo "<br><br><a href='http://cgi.soic.indiana.edu/~mlightca/Team73/tp_team73.php'>Go Back</a>";
mysqli_close($con);
?>