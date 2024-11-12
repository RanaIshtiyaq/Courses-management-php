<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!-- Dashboard Content -->
<?php include 'header.php'; ?>

<div class="container mt-5">
    <h2>Welcome, <?php echo $_SESSION['user_name']; ?></h2>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<?php include 'footer.php'; ?>
