<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'Lab_5b');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Fetch user details
    $sql = "SELECT * FROM users WHERE matric = '$matric'";
    $result = $conn->query($sql);
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>User not found!</div>";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $sql = "UPDATE users SET name='$name', password='$password', role='$role' WHERE matric='$matric'";
    if ($conn->query($sql)) {
        header('Location: display.php');
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Edit User</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="matric" value="<?= $user['matric'] ?>">
                <div class="mb-3">
                    <label for="matric" class="form-label">Matric</label>
                    <input type="text" class="form-control" id="matric" name="matric" value="<?= $user['matric'] ?>" disabled>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= $user['name'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="lecture" <?= $user['role'] === 'lecture' ? 'selected' : '' ?>>Lecture</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="display.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
