<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
} 
require '../db.php';

$errors = [];
$successMessage = '';

// Handle edit and delete
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from POST request
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];
    $studentId = $_POST['id']; // Get student ID for edit

    // Backend validation
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($age)) {
        $errors[] = "Age is required.";
    } elseif (!is_numeric($age)) {
        $errors[] = "Age must be a valid number.";
    }

    if (empty($errors)) {
        $conn = connect();

        // Edit operation
        if ($studentId) {
            // Update the student record in the database
            $stmt = $conn->prepare("UPDATE students SET name = ?, email = ?, age = ? WHERE id = ?");
            $stmt->bind_param("ssii", $name, $email, $age, $studentId);
            $stmt->execute();
            $stmt->close();

            $successMessage = "Student record updated successfully!";
        } else {
            // Insert operation
            $stmt = $conn->prepare("INSERT INTO students (name, email, age) VALUES (?, ?, ?)");
            $stmt->bind_param("ssi", $name, $email, $age);
            $stmt->execute();
            $stmt->close();

            $successMessage = "Student added successfully!";
        }

        $conn->close();
        
        $_SESSION['success_message'] = $successMessage;
        header("Location: view.php");
        exit();
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $studentId = $_GET['delete'];
    $conn = connect();
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
    
    $_SESSION['success_message'] = "Student deleted successfully!";
    header("Location: view.php");
    exit();
}

// Fetch student data for edit
if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $conn = connect();
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
} else {
    $student = null;
}
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2><?php echo $student ? "Edit Student" : "Add Student"; ?></h2>
        </div>
        <div class="card-body">
            <form method="post" novalidate id="studentForm">
                <!-- Hidden ID for Edit -->
                <input type="hidden" name="id" value="<?php echo $student ? $student['id'] : ''; ?>">

                <!-- Name field -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required value="<?php echo isset($student['name']) ? $student['name'] : ''; ?>">
                    <div class="invalid-feedback">Please enter the student's name.</div>
                </div>

                <!-- Email field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="<?php echo isset($student['email']) ? $student['email'] : ''; ?>">
                    <div class="invalid-feedback">Please enter a valid email.</div>
                </div>

                <!-- Age field -->
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" class="form-control" id="age" name="age" required value="<?php echo isset($student['age']) ? $student['age'] : ''; ?>">
                    <div class="invalid-feedback">Please enter the student's age.</div>
                </div>

                <!-- Display backend validation errors if any -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <button type="submit" class="btn btn-primary"><?php echo $student ? "Update Student" : "Add Student"; ?></button>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>

<!-- Enabling Bootstrap validation and clearing the form after success -->
<script>
    (function () {
        'use strict';
        var forms = document.querySelectorAll('form');
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    } else {
                        // Clear the form after successful submission
                        setTimeout(function () {
                            form.reset(); // This will clear the form
                        }, 1000); // Wait for a second to let success message appear
                    }
                    form.classList.add('was-validated');
                }, false);
            });
    })();
</script>
