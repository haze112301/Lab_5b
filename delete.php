<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    $conn = new mysqli('localhost', 'root', '', 'Lab_5b');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $sql = "DELETE FROM users WHERE matric = '$matric'";
    if ($conn->query($sql)) {
        header('Location: display.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }

    $conn->close();
} else {
    header('Location: display.php');
    exit;
}
?>
