<?php
session_start();
echo "<pre>";
print_r($_SESSION);
exit;


if (!isset($_SESSION['username'])) {
    die("Not logged in");

}
if ($_SESSION['role'] !== 'admin') {
    die("You are not admin");
}

include 'db.php';

$id = $_GET['id'];
$query = "DELETE FROM posts WHERE id = $id";
mysqli_query($conn, $query);

header("Location: posts.php");
exit;