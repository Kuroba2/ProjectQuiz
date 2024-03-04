<?php
    // Kết nối đến cơ sở dữ liệu MySQL
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "exammanagement";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Biến chứa giá trị nhập vào
    $input = $_POST['subjectName'];

    // Thực hiện câu truy vấn
    $sql = "SELECT student.StudentID, Name, subject.subjectID, subjectName, Participation AS Completed, Score, Time 
            FROM student 
            JOIN student_subject on student.StudentID = student_subject.StudentID 
            JOIN subject ON student_subject.SubjectID = subject.SubjectID
            WHERE Name = '$input' or student.StudentID = '$input'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {    
        // In ra dữ liệu
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr>";
        echo "<th style='border: 1px solid black;'>StudentID</th>";
        echo "<th style='border: 1px solid black;'>Name</th>";
        echo "<th style='border: 1px solid black;'>subjectID</th>";
        echo "<th style='border: 1px solid black;'>subjectName</th>";
        echo "<th style='border: 1px solid black;'>Completed</th>";
        echo "<th style='border: 1px solid black;'>Score</th>";
        echo "<th style='border: 1px solid black;'>Time</th>";
        echo "</tr>";
       
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 1px solid black;'>" . $row["StudentID"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Name"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["subjectID"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["subjectName"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Completed"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Score"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Time"]. "</td>";
            echo "</tr>";
        }
    
        echo "</table>";
    }
    
    $conn->close();
?>
