<!DOCTYPE HTML>
<html>

<head>
<title>Query Result</title>
<style>
div.header{
  font-size: 25px;
  margin-left: 405px;
  margin-bottom: 10px;
  color: green;
  padding-top: 20px;
}
table.records{
  margin-left: 290px;
  background-color: cornsilk;
  border: 1px solid black;
  border-radius: 5px;
  text-align: center;
  padding-top: 8px;
  padding-left: 10px;
  padding-right: 10px;
  padding-bottom: 5px;
}
div.query{
  color: midnightblue;
  font-size: 20px;
  margin-left: 150px;
}
.date{
  padding-left: 3px;
  padding-right: 20px;
}
</style>
</head>

<body style="background-color:#D8D8D8;">
<div class="header"> Employees database </div>
<?php

  // set connection parameters
  $servername = "localhost";
  $username = "root";
  $password = "asd";
  $db = "employees";
  
  // Create connection
  $conn = new mysqli($servername, $username, $password, $db);
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  //echo "Connected successfully to the database. </br> </br> ";
  
  function query1($result){  
   if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"records\">
              <tr>
              <th>Emp_no</th> <th>First Name</th> <th>Last Name</th> <th>Gender</th> <th>Hire Date</th> <th>Dept_no</th> <th>Dept_name</th>
              </tr>"; 
        while($row = $result->fetch_assoc()) {
           echo "<tr>";
           echo "<td>".$row["emp_no"]."</td>";
           echo "<td>".$row["first_name"]."</td>";
           echo "<td>".$row["last_name"]."</td>";
           echo "<td>".$row["gender"]."</td>";
           echo "<td>".$row["hire_date"]."</td>";
           echo "<td>".$row["dept_no"]."</td>";
           echo "<td>".$row["dept_name"]."</td>";
           echo "</tr>";
        }
        echo "</table>";
      }  
      else {
        echo "No data found in the database!</br>";
      } 
  }
  
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = $_REQUEST['emp_id'];
    $last_name = $_REQUEST['last_name'];
    if( !empty($emp_id) ){
      // Make the SQL query -> Query 1a
      echo "<div class=\"query\"> Query 1a: Using Emp_no </div> </br>";
      $sql = "select temp.*, departments.dept_name from departments inner join (select employees.*, dept_emp.dept_no from employees inner join dept_emp on dept_emp.emp_no=employees.emp_no)temp on temp.dept_no=departments.dept_no where emp_no=$emp_id;";
      $result = $conn->query($sql);
      query1($result);
    }
    elseif (!empty($last_name)) {
      // SQL Query 1b
      echo "<div class=\"query\"> Query 1b: Using last name </div> </br>";
      $sql = "select temp.*, departments.dept_name from departments inner join (select employees.*, dept_emp.dept_no from employees inner join dept_emp on dept_emp.emp_no=employees.emp_no)temp on temp.dept_no=departments.dept_no where last_name=\"$last_name\";";
      $result = $conn->query($sql);
      query1($result);
    }
    elseif ( isset($_POST['dept_name']) and $_POST['dept_name']!="dept_name" ) {
      // SQL Query 1c 
      echo "<div class=\"query\"> Query 1c: Using Department </div> </br>";
      $dept_name = $_POST['dept_name'];
      $sql = "select temp.*, departments.dept_name from departments inner join (select employees.*, dept_emp.dept_no from employees inner join dept_emp on dept_emp.emp_no=employees.emp_no)temp on temp.dept_no=departments.dept_no where dept_name=\"$dept_name\";";
      $result = $conn->query($sql);
      query1($result);
    }
    elseif ( isset($_POST['radio']) ) {
      echo "<div class=\"query\"> Query 2: Largest Department </div> </br>";
      $sql = "SELECT departments.*, temp.magnitude FROM departments INNER JOIN (select dept_no, count(dept_no) as magnitude from dept_emp GROUP BY dept_no ORDER BY magnitude DESC LIMIT 1)temp WHERE departments.dept_no=temp.dept_no;";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo "<table class=\"records\"> <tr> <th>Dept_no</th> <th>Dept_name</th> <th>Count</th> </tr>";
       // while($row = $result->fetch_assoc()) {
          $row = $result->fetch_assoc();
          echo "<tr>";
          echo "<td>" . $row["dept_no"]. "</td>";
          echo "<td>" . $row["dept_name"]. "</td>";
          echo "<td>" . $row['magnitude'] . "</td>";
          echo "</tr>";
        //}
        echo "</table>";
      }
    }
    elseif ( isset($_POST['dept_name_tenure']) and $_POST['dept_name_tenure']!="dept_name" ) {
      $dept = $_POST['dept_name_tenure'];
      echo "<div class=\"query\"> Query 3: Ordered Tenure of Department --> ".$dept. " </div> </br>";
      $sql = "SELECT * FROM (SELECT temp.emp_no, employees.first_name, employees.last_name, employees.gender, temp.from_date, temp.to_date, DATEDIFF(temp.to_date, temp.from_date)as tenure FROM employees INNER JOIN (SELECT dept_emp.*, departments.dept_name FROM dept_emp INNER JOIN departments ON departments.dept_no=dept_emp.dept_no WHERE dept_name=\"$dept\")temp ON temp.emp_no=employees.emp_no)t ORDER BY tenure DESC;";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo "<table class=\"records\"> <tr> 
             <th>emp_no</th> <th>first_name</th> <th>last_name</th> <th>gender</th> <th class=\"date\">fom_date</th> <th>to_date</th> <th>tenure(days)</th> 
             </tr>";
        while( $row = $result->fetch_assoc() ){
          echo "<tr>";
          echo "<td>".$row['emp_no']."</td>";
          echo "<td>".$row['first_name']."</td>";
          echo "<td>".$row['last_name']."</td>";
          echo "<td>".$row['gender']."</td>";
          echo "<td class=\"date\">".$row['from_date']."</td>";
          echo "<td>".$row['to_date']."</td>";
          echo "<td>".$row['tenure']."</td>";
          echo "</tr>";
        }
        echo "</table>";
      }
      else{
        echo "No data found in the database!</br>";
      }
    }
    elseif ( isset($_POST['gender_ratio']) and $_POST['gender_ratio']!="dept_name" ) {
      $dept = $_POST['gender_ratio'];
      echo "<div class=\"query\"> Query 4: Gender Ratio of Department --> ".$dept. " </div> </br>";
      $sql = "SELECT COUNT(CASE WHEN gender='M' THEN emp_no END) AS males, COUNT(CASE WHEN gender='F' THEN emp_no END) AS females FROM (SELECT temp.emp_no, employees.gender FROM employees INNER JOIN (SELECT dept_emp.emp_no, dept_emp.dept_no, departments.dept_name FROM dept_emp INNER JOIN departments ON departments.dept_no=dept_emp.dept_no WHERE dept_name=\"$dept\")temp ON temp.emp_no=employees.emp_no)t;";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $males = $row['males'];
      $females = $row['females'];
      //$ratio = $males/$females;
      echo "<table class=\"records\"> <tr> <th>Males</th> <th>Females</th> </tr>";
      echo "<tr> <td>".$males." </td> <td>".$females."</td> </tr> </table>";
      echo "</br> <div class=\"query\">Gender Ratio (male/female): ". ($males / $females) . "</div> </br>";
    }
    elseif ( isset($_POST['gender_pay_ratio']) and $_POST['gender_pay_ratio']!="dept_name" ) {
      $dept = $_POST['gender_pay_ratio'];
      echo "<div class=\"query\"> Query 5: Gender Pay Ratio of Department --> ".$dept. " </div> </br>";
      $sql = "SELECT dept_no FROM departments WHERE dept_name=\"$dept\"";
      $result = $conn->query($sql);
      $row = $result->fetch_assoc();
      $dept_n = $row['dept_no'];
      $sql = "SELECT title, SUM(CASE WHEN gender='M' then salary END)as male_salary, SUM(CASE WHEN gender='F' then salary END)as female_salary FROM (SELECT temp2.*, employees.gender FROM (SELECT emp_no, title, AVG(salary) as salary FROM (SELECT * from (SELECT s.salary, s.sfrom_date, s.sto_date, t.emp_no, t.tfrom_date, t.tto_date, t.title FROM ( SELECT * FROM (SELECT salaries.salary, salaries.from_date AS sfrom_date, salaries.to_date AS sto_date, dept_emp.* FROM salaries INNER JOIN dept_emp ON dept_emp.emp_no=salaries.emp_no)temp WHERE ( (sfrom_date>from_date AND sfrom_date<to_date) OR (sto_date>from_date AND sto_date<to_date) ) )s INNER JOIN ( SELECT * FROM (SELECT dept_emp.*, titles.title, titles.from_date AS tfrom_date, titles.to_date AS tto_date FROM dept_emp INNER JOIN titles ON titles.emp_no=dept_emp.emp_no WHERE dept_no='$dept_n')temp WHERE ( (tto_date>=from_date AND tto_date<=to_date) OR (tfrom_date>=from_date AND tfrom_date<=to_date) ) )t ON t.emp_no=s.emp_no)temp WHERE ( (tfrom_date>=sfrom_date AND tto_date<=sto_date) OR (tto_date>=sfrom_date AND tto_date<=sto_date) ) )temp1 GROUP BY emp_no, title)temp2 INNER JOIN employees ON employees.emp_no=temp2.emp_no)temp3 GROUP BY title;";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        echo "<table class=\"records\"> <tr> 
             <th> Title </th> <th> Male Salary </th> <th> Female Salary </th> <th> Ratio(male/female) </th>
             </tr>";
        while( $row = $result->fetch_assoc() ){
          echo "<tr>";
          echo "<td>".$row['title']."</td>";
          echo "<td>".$row['male_salary']."</td>";
          echo "<td>".$row['female_salary']."</td>";
          echo "<td>".($row['male_salary'] / $row['female_salary'])."</td>";
          echo "</tr>";
        }
        echo "</table>";
      }
      else{
        echo "No data found in the database!</br>";
      }
    }
  }
?>
</body>

</html>
