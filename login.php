<?php
session_start();
require 'db.php';

// Define hardcoded email and password for the login process
define('ADMIN_EMAIL', 'my_wp_protfolio');
define('ADMIN_PASSWORD_HASH', '112233'); // You can hash this password for better security

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Backend validation
    $errors = [];

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    // Proceed if there are no validation errors
    if (empty($errors)) {
        // Check if the entered email and password match the hardcoded values
        if ($email == ADMIN_EMAIL && $password == ADMIN_PASSWORD_HASH) {
            // Correct credentials, start session
            $_SESSION['user_id'] = 1; // Store user ID (could be a real user ID from the database)
            $_SESSION['user_name'] = 'Admin User'; // Store the user's name or username
            header("Location: index.php"); // Redirect to the dashboard page
            exit();
        } else {
            $errors[] = "Invalid email or password.";
        }
    }
}

$title = "Login - Student Management";
include 'header.php';  // Include the header
?>

<!-- Login Form -->
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>Login</h2>
        </div>
        <div class="card-body">
            <form method="post" novalidate>
                <!-- Email field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <!-- Password field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <!-- Display errors if any -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</div>

<?php
include 'footer.php';  // Include the footer
?>
