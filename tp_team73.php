<!doctype html>
<html>
<title>Team 73 - Main PHP</title>
<body>

<h2>1b. Produce a class roster for a *specified section* sorted by student’s last name, first name.
At the end, include the average grade (GPA for the class).</h2>
<p style="width:30%"><font size = "1" color = "grey">SELECT CONCAT(st.lname, ' ', st.fname) as name, ' ' as average
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
GROUP BY name;
</font></p>
<form action="select_1b.php" method="POST">
Section Number: <select name='section'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct s.sectionID as section, CONCAT(c.course_num,' ',TIME_FORMAT(s.time, '%h:%i%p')) as course  FROM tp_section as s, tp_course as c WHERE s.courseID = c.courseID");
while($row = mysqli_fetch_assoc($result)){
	unset($sid, $course_num);
	$sid = $row['section'];
	$course_num = $row['course'];
	echo '<option value="'.$sid.'">'.$course_num.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Roster">
</form>

<h2>2a. Produce a list of rooms that are equipped with *some feature*—e.g., “wired instructor
station”.</h2>
<p style="width:30%"><font size = "1" color = "grey">
SELECT CONCAT (b.building_name, ' ', r.room_num) as room
FROM tp_building as b, tp_room as r, tp_room_feature as rf
WHERE b.buildingID = r.buildingID and r.roomID = rf.roomID and rf.feature = '$sanfeature';
</font></p>
<form action="select_2a.php" method="POST">
Room Feature: <select name='feature'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct feature FROM tp_room_feature");
while($row = mysqli_fetch_assoc($result)){
	unset($feature);
	$feature = $row['feature'];
	echo '<option value="'.$feature.'">'.$feature.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Rooms">
</form>

<h2>4a. Produce a list of students who are eligible to register for a *specified course* that 
has a prerequisite.</h2>
<p style="width:30%"><font size = "1" color = "grey">SELECT CONCAT(fname,' ',lname) as name
FROM tp_student
WHERE studentID in (
SELECT st.studentID
FROM tp_course_prereq as cp, tp_section as s, tp_course_details as cd, 
tp_student as st, tp_course as c
WHERE cp.courseID = $sannum and cp.prereqID = s.courseID and cd.sectionID = s.sectionID
and st.studentID = cd.studentID
);</font></p>
<form action="select_4a.php" method="POST">
Course Number: <select name='four'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct courseID, course_num FROM tp_course");
while($row = mysqli_fetch_assoc($result)){
	unset($id, $name);
	$cid = $row['courseID'];
	$num = $row['course_num'];
	echo '<option value="'.$cid.'">'.$num.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Students">
</form>


<h2>5c.  Produce a chronological list of all courses taken by a *specified student*. Show grades
earned. Include overall hours earned and GPA at the end. (Hint: An F does not earn
hours)</h2>
<p style="width:30%"><font size = "1" color = "grey">SELECT semester_title, start_date, course_num, letter_grade
FROM tp_sscd
WHERE studentID = $sannum
UNION
SELECT 'Total Credits' as semester_title, SUM(CASE WHEN grade_points > 0 THEN credit_hours ELSE 0 END) as start_date, 'GPA' as course_num, CAST((SUM(grade_points)/ COUNT(grade_points)) as decimal(11,3)) as letter_grade
FROM tp_sscd
WHERE studentID = $sannum
ORDER BY if(semester_title !=  'Total Credits', 0, 1), start_date;
</font></p>
<form action="select_5c.php" method="POST">
Student: <select name='name'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct studentID, CONCAT(fname, ' ',lname) as name FROM tp_student");
while($row = mysqli_fetch_assoc($result)){
	unset($id, $name);
	$sid = $row['studentID'];
	$name = $row['name'];
	echo '<option value="'.$sid.'">'.$name.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Courses">
</form>


<h2>7a. Produce an alphabetical list of students with their majors who are advised by a
*specified advisor*.</h2>
<p style="width:30%"><font size = "1" color = "grey">
SELECT CONCAT(s.fname, ' ', s.lname) as name, m.major_name
FROM tp_student as s, tp_advise as a, tp_major as m
WHERE s.studentID = a.studentID and a.employeeID = $sanadvise and m.studentID = s.studentID
ORDER BY name ASC;</font></p>
<form action="select_7a.php" method="POST">
Advisor: <select name='advisor'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct employeeID, CONCAT (fname, ' ', lname) as name
FROM tp_employee WHERE rank = 'Advisor'");
while($row = mysqli_fetch_assoc($result)){
	unset($id, $name);
	$id = $row['employeeID'];
	$name = $row['name'];
	echo '<option value="'.$id.'">'.$name.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Advisor">
</form>

<h2>9b.  Produce a list of students with hours earned who have met graduation requirements for
a *specified major*.</h2>
<p style="width:30%"><font size = "1" color = "grey">
SELECT CONCAT (st.fname, ' ', st.lname) as name FROM tp_student as st, tp_major as m 
WHERE m.studentID = st.studentID and m.major_name = '$sanmajor' and 
m.required_credits <= ( SELECT SUM(c.credit_hours) FROM tp_course as c, tp_course_details as cd, tp_section as se
WHERE se.courseID = c.courseID and cd.sectionID = se.sectionID and st.studentID = cd.studentID) GROUP BY name;</font></p>
<form action="select_9b.php" method="POST">
Major: <select name='major'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct major_name FROM tp_major;");
while($row = mysqli_fetch_assoc($result)){
	unset($name);
	$name = $row['major_name'];
	echo '<option value="'.$name.'">'.$name.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Major">
</form>

<h2>Additional 1 - Produce a list of students for a *specified major* and display their information.</h2>
<p style="width:30%"><font size = "1" color = "grey">
SELECT CONCAT(fname, ' ', lname) as name, address, stu_number, email_address, CONCAT(parent_fname, ' ', parent_lname) as parent_name, par_number
FROM tp_scm
WHERE major_name = '$sanmajor'
group by name
UNION ALL
SELECT 'Total Students' as name, 
(SELECT count(*) from tp_student as st, tp_major as m WHERE st.studentID = m.studentID and m.major_name = '$sanmajor')
as address, ' ' as stu_number, 'Required Credits' as email_address, required_credits as parent_name, ' ' as par_number
from tp_scm
where major_name = '$sanmajor'
group by name;</font></p>
<form action="select_add1.php" method="POST">
Major: <select name='major1'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct major_name FROM tp_major;");
while($row = mysqli_fetch_assoc($result)){
	unset($name);
	$name = $row['major_name'];
	echo '<option value="'.$name.'">'.$name.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Major">
</form>


<h2>Additional 2 - Produce a list of sections for a *specified course*. Show the employee who 
teaches the section, their information, and what department they are in.</h2>
<p style="width:30%"><font size = "1" color = "grey">SELECT CONCAT(s.semester_title,' ', TIME_FORMAT(s.time, '%h:%i%p')) as section, s.sectionID, CONCAT(e.fname, ' ', e.lname) as name, ep.number as phone, ee.email_address as email, d.department_name as department
FROM tp_section as s, tp_employee as e, tp_employee_phone as ep, tp_employee_email as ee, tp_department as d, tp_course as c, tp_employee_section as es
WHERE c.courseID = $sannum and s.courseID = c.courseID and es.sectionID = s.sectionID and e.employeeID = es.employeeID and c.departmentID = d.departmentID and e.employeeID = ep.employeeID and e.employeeID = ee.employeeID
GROUP BY s.sectionID, name;</font></p>
<form action="select_add2.php" method="POST">
Course: <select name='course'>
<?php
$conn = mysqli_connect("db.soic.indiana.edu","i308f17_team73","my+sql=i308f17_team73","i308f17_team73");
if (!$conn) {
	die("Connection failed:".mysqli_connect_error());
}
$result = mysqli_query($conn,"SELECT distinct courseID, course_num FROM tp_course");
while($row = mysqli_fetch_assoc($result)){
	unset($id, $name);
	$id = $row['courseID'];
	$name = $row['course_num'];
	echo '<option value="'.$id.'">'.$name.'</option>';
}
?>
</select>
<br><br>
<input type="submit" value="Select Course">
</form>


</body>
</html>