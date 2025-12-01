<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['student_name'];
    $math = $_POST['mathematics'];
    $eng  = $_POST['english'];
    $kis  = $_POST['kiswahili'];

    echo "<h2>Marks Submitted Successfully</h2>";
    echo "Student Name: $name<br>";
    echo "Mathematics: $math<br>";
    echo "English: $eng<br>";
    echo "Kiswahili: $kis<br>";
}
?>
