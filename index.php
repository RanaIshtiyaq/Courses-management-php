<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}   

$title = "Dashboard"; // You can dynamically set the title if needed
include 'header.php';  // Include the header
?>

<div class="container mt-5">
    <h1>Welcome to the Dashboard</h1>
    <p>Choose an option below to manage students, teachers, or courses.</p>
    
    <div class="list-group">
        <a href="students/view.php" class="list-group-item list-group-item-action">Students</a>
        <a href="teachers/view.php" class="list-group-item list-group-item-action">View Teachers</a>
        <a href="courses/view.php" class="list-group-item list-group-item-action">View Courses</a>
        <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
    </div>
</div>

<?php
include 'footer.php';  // Include the footer
