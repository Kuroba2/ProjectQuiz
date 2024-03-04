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
    $input = $_POST['subjectNameExam'];

    // Thực hiện câu truy vấn
    $sql = "SELECT student.StudentID, Name, subject.SubjectID, SubjectName, Question, Prediction, Answer
            FROM student
            JOIN student_exam ON student.StudentID = student_exam.StudentID
            JOIN subject on student_exam.SubjectID = subject.SubjectID
            WHERE subject.SubjectName = '$input'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {    
        // In ra dữ liệu
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr>";
        echo "<th style='border: 1px solid black;'>StudentID</th>";
        echo "<th style='border: 1px solid black;'>Name</th>";
        echo "<th style='border: 1px solid black;'>SubjectID</th>";
        echo "<th style='border: 1px solid black;'>SubjectName</th>";
        echo "<th style='border: 1px solid black;'>Question</th>";
        echo "<th style='border: 1px solid black;'>Prediction</th>";
        echo "<th style='border: 1px solid black;'>Answer</th>";
        echo "</tr>";
       
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td style='border: 1px solid black;'>" . $row["StudentID"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Name"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["SubjectID"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["SubjectName"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Question"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Prediction"]. "</td>";
            echo "<td style='border: 1px solid black;'>" . $row["Answer"]. "</td>";
            echo "</tr>";
        }
    
        echo "</table>";
    } else {
        echo "No results found.";
    }
    
    $conn->close();
?>
