<?php
session_start();
include 'db.php';

if(!isset($_SESSION['username'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['submit'])){
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validation
    if(empty($title) || empty($content)){
        die("Title and Content required");
    }

    // Image upload
    $image = "";
    if(!empty($_FILES['image']['name'])){
        $allowed = ['jpg','jpeg','png'];
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        if(!in_array($ext, $allowed)){
            die("Only JPG, JPEG, PNG allowed");
        }

        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);
    }

    // Prepared Statement
    $stmt = $conn->prepare(
        "INSERT INTO posts (title, content, image) VALUES (?, ?, ?)"
    );
    $stmt->bind_param("sss", $title, $content, $image);
    $stmt->execute();

    header("Location: posts.php");
}
?>

<h2>Add Post</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="Title"><br><br>
    <textarea name="content" placeholder="Content"></textarea><br><br>
    <input type="file" name="image"><br><br>
    <button name="submit">Add Post</button>
</form>