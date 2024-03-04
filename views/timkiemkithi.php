<?php
        // Kết nối đến cơ sở dữ liệu MySQL
        $servername = "localhost:5500";
        $username = "root";
        $password = "";
        $dbname = "exammanagement";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Kiểm tra kết nối
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Lấy dữ liệu từ input
            $subjectName = $_POST["subjectName"];

            // Thực hiện truy vấn
            $sql = "SELECT student.StudentID, Name, subject.subjectID, subjectName, Score
                    FROM student 
                    JOIN student_subject on student.StudentID = student_subject.StudentID 
                    JOIN subject ON student_subject.SubjectID = subject.SubjectID
                    WHERE subjectName = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $subjectName);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>Kết quả tìm kiếm cho môn học: $subjectName</h2>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr>";
                echo "<th style='border: 1px solid black;'>StudentID</th>";
                echo "<th style='border: 1px solid black;'>Name</th>";
                echo "<th style='border: 1px solid black;'>SubjectID</th>";
                echo "<th style='border: 1px solid black;'>Subject Name</th>";
                echo "<th style='border: 1px solid black;'>Score</th>";
                echo "</tr>";

                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td style='border: 1px solid black;'>" . $row["StudentID"]. "</td>";
                    echo "<td style='border: 1px solid black;'>" . $row["Name"]. "</td>";
                    echo "<td style='border: 1px solid black;'>" . $row["subjectID"]. "</td>";
                    echo "<td style='border: 1px solid black;'>" . $row["subjectName"]. "</td>";
                    echo "<td style='border: 1px solid black;'>" . $row["Score"]. "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $sheet = $objPHPExcel->getActiveSheet();
                
                // Ghi tiêu đề cột
                $sheet->setCellValue('A1', 'StudentID');
                $sheet->setCellValue('B1', 'Name');
                $sheet->setCellValue('C1', 'SubjectID');
                $sheet->setCellValue('D1', 'Subject Name');
                $sheet->setCellValue('E1', 'Score');

                // Ghi dữ liệu từ kết quả truy vấn vào các ô tương ứng
                $row = 2;
                while($row_data = $result->fetch_assoc()) {
                    $sheet->setCellValue('A' . $row, $row_data['StudentID']);
                    $sheet->setCellValue('B' . $row, $row_data['Name']);
                    $sheet->setCellValue('C' . $row, $row_data['subjectID']);
                    $sheet->setCellValue('D' . $row, $row_data['subjectName']);
                    $sheet->setCellValue('E' . $row, $row_data['Score']);
                    $row++;
                }

                // Thiết lập định dạng tệp Excel
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="student_results.xlsx"');
                header('Cache-Control: max-age=0');
                
                // Tải xuống tệp Excel
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('php://output');
            } else {
                echo "<p>Không có kết quả nào cho môn học $subjectName</p>";
            }
            $stmt->close();
        }
        $stmt->close();
        $conn->close();
    ?>