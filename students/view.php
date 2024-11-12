<?php
session_start(); // Start the session
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // Redirect to the login page if not logged in
    exit();
} 
// Check if there is a success message in the session
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // Clear the success message after showing it
}

require '../db.php';

// Fetch students from the database
$conn = connect();
$result = $conn->query("SELECT * FROM students");
$students = [];

if ($result->num_rows > 0) {
    // Fetch all students into an array
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
} else {
    $students = [];
}

$conn->close();
?>

<?php include '../header.php'; ?>

<div class="container mt-5">
<div class="card">
<div class="card-header justify-content-between d-flex">
<h2>Students List</h2>
<a href="add.php" class='btn btn-success'>Add</a>
</div>
<div class="card-body">
    
    <!-- Display success message -->
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($successMessage); ?>
        </div>
    <?php endif; ?>

    <!-- Check if there are students -->
    <?php if (count($students) > 0): ?>
        <table class="table table-striped table-sm text-center">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo $student['name']; ?></td>
                        <td><?php echo $student['email']; ?></td>
                        <td><?php echo $student['age']; ?></td>
                        <td>
                        <a href="add.php?id=<?php echo $student['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="add.php?delete=<?php echo $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?')">Delete</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No students found.</div>
    <?php endif; ?>
</div>
</div>
</div>

<?php include '../footer.php'; ?>
