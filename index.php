<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'Lab_5b');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login logic
        $matric = $_POST['login_matric'];
        $password = $_POST['login_password'];

        $sql = "SELECT * FROM users WHERE matric = '$matric'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['matric'] = $user['matric'];
                header('Location: display.php');
                exit;
            }
        }
        $login_error = "Invalid matric or password!";
    } elseif (isset($_POST['register'])) {
        // Registration logic
        $matric = $_POST['register_matric'];
        $name = $_POST['register_name'];
        $password = password_hash($_POST['register_password'], PASSWORD_DEFAULT);
        $role = $_POST['register_role'];

        $sql = "INSERT INTO users (matric, name, password, role) VALUES ('$matric', '$name', '$password', '$role')";
        if ($conn->query($sql)) {
            $register_success = "Registration successful! You can now log in.";
        } else {
            $register_error = "Error: " . $conn->error;
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .toggle-btn {
            cursor: pointer;
            text-decoration: underline;
        }
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card form-container">
        <div class="card-header bg-primary text-white text-center">
            <h3 id="form-title">Login</h3>
        </div>
        <div class="card-body">
            <!-- Login Form -->
            <form method="POST" action="" id="login-form" class="form-section active">
                <?php if (isset($login_error)): ?>
                    <div class="alert alert-danger"><?= $login_error ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="login_matric" class="form-label">Matric</label>
                    <input type="text" class="form-control" id="login_matric" name="login_matric" required>
                </div>
                <div class="mb-3">
                    <label for="login_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="login_password" name="login_password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                <p class="mt-3 text-center">Don't have an account? <span class="toggle-btn" onclick="toggleForm('register')">Register</span></p>
            </form>

            <!-- Register Form -->
            <form method="POST" action="" id="register-form" class="form-section">
                <?php if (isset($register_success)): ?>
                    <div class="alert alert-success"><?= $register_success ?></div>
                <?php endif; ?>
                <?php if (isset($register_error)): ?>
                    <div class="alert alert-danger"><?= $register_error ?></div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="register_matric" class="form-label">Matric</label>
                    <input type="text" class="form-control" id="register_matric" name="register_matric" required>
                </div>
                <div class="mb-3">
                    <label for="register_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="register_name" name="register_name" required>
                </div>
                <div class="mb-3">
                    <label for="register_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="register_password" name="register_password" required>
                </div>
                <div class="mb-3">
                    <label for="register_role" class="form-label">Role</label>
                    <select class="form-select" id="register_role" name="register_role" required>
                        <option value="student">Student</option>
                        <option value="lecture">Lecture</option>
                    </select>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                <p class="mt-3 text-center">Already have an account? <span class="toggle-btn" onclick="toggleForm('login')">Login</span></p>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleForm(form) {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const formTitle = document.getElementById('form-title');

        if (form === 'login') {
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
            formTitle.innerText = 'Login';
        } else {
            loginForm.classList.remove('active');
            registerForm.classList.add('active');
            formTitle.innerText = 'Register';
        }
    }
</script>
</body>
</html>
