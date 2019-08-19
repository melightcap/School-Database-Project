/* 1b.  Produce a class roster for a *specified section* sorted by student’s last name, first name.
At the end, include the average grade (GPA for the class)*/
SELECT CONCAT(st.lname, ' ', st.fname) as name, ' ' as average
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

/* 2a.  Produce a list of rooms that are equipped with *some feature*—e.g., “wired instructor
station”.*/
SELECT CONCAT (b.building_name, ' ', r.room_num) as room
FROM tp_building as b, tp_room as r, tp_room_feature as rf
WHERE b.buildingID = r.buildingID and r.roomID = rf.roomID and rf.feature = '$sanfeature';

/* 4a.  Produce a list of students who are eligible to register for a *specified course* that 
has a prerequisite. - change so that the course_num works with php */

SELECT CONCAT(fname,' ',lname) as name
FROM tp_student
WHERE studentID in (
SELECT st.studentID
FROM tp_course_prereq as cp, tp_section as s, tp_course_details as cd, 
tp_student as st, tp_course as c
WHERE cp.courseID = $sannum and cp.prereqID = s.courseID and cd.sectionID = s.sectionID
and st.studentID = cd.studentID
);


/* 5c.  Produce a chronological list of all courses taken by a *specified student*. Show grades
earned. Include overall hours earned and GPA at the end. (Hint: An F does not earn
hours.)*/
SELECT semester_title, start_date, course_num, letter_grade
FROM tp_sscd
WHERE studentID = $sannum
UNION
SELECT 'Total Credits' as semester_title, SUM(CASE WHEN grade_points > 0 THEN credit_hours ELSE 0 END) as start_date, 'GPA' as course_num, CAST((SUM(grade_points)/ COUNT(grade_points)) as decimal(11,3)) as letter_grade
FROM tp_sscd
WHERE studentID = $sannum
ORDER BY if(semester_title !=  'Total Credits', 0, 1), start_date;

/* 7a.  Produce an alphabetical list of students with their majors who are advised by a
*specified advisor*.*/
SELECT CONCAT(s.fname, ' ', s.lname) as name, m.major_name
FROM tp_student as s, tp_advise as a, tp_major as m
WHERE s.studentID = a.studentID and a.employeeID = $sanadvise and m.studentID = s.studentID
ORDER BY name ASC;

/* 9b.  Produce a list of students with hours earned who have met graduation requirements for
a *specified major*.*/
SELECT CONCAT (st.fname, ' ', st.lname) as name FROM tp_student as st, tp_major as m 
WHERE m.studentID = st.studentID and m.major_name = '$sanmajor' and 
m.required_credits <= ( SELECT SUM(c.credit_hours) FROM tp_course as c, tp_course_details as cd, tp_section as se
WHERE se.courseID = c.courseID and cd.sectionID = se.sectionID and st.studentID = cd.studentID) GROUP BY name;


/*Additional 1 - Choose a major from the list, and have it display all information (phone, 
email, parentphone, etc.) about students taking that major, the total amount of students 
taking it, and the majors required credits*/

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
group by name;


/*Additional 2 - Produce a list of sections for a *specified course*. Show the employee who 
teaches the section, their information, and what department they are in*/

SELECT CONCAT(s.semester_title,' ', TIME_FORMAT(s.time, '%h:%i%p')) as section, s.sectionID, CONCAT(e.fname, ' ', e.lname) as name, ep.number as phone, ee.email_address as email, d.department_name as department
FROM tp_section as s, tp_employee as e, tp_employee_phone as ep, tp_employee_email as ee, tp_department as d, tp_course as c, tp_employee_section as es
WHERE c.courseID = $sannum and s.courseID = c.courseID and es.sectionID = s.sectionID and e.employeeID = es.employeeID and c.departmentID = d.departmentID and e.employeeID = ep.employeeID and e.employeeID = ee.employeeID
GROUP BY s.sectionID, name;