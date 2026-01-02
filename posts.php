
<?php
session_start();
include 'db.php';

// ---------- LOGIN CHECK ----------
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// ---------- SEARCH ----------
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$whereSql = "";
$params = [];

if ($search !== "") {
    $whereSql = "WHERE title LIKE ? OR content LIKE ?";
    $like = "%" . $search . "%";
}

// ---------- FETCH POSTS ----------
if ($whereSql !== "") {
    $stmt = $conn->prepare("SELECT * FROM posts $whereSql ORDER BY id DESC");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = mysqli_query($conn, "SELECT * FROM posts ORDER BY id DESC");
}
?><!DOCTYPE html><html>
<head>
    <title>Posts</title>
    <style>
        body { font-family: Arial; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        img { max-width: 80px; }
    </style>
</head>
<body><h2>All Posts</h2><form method="get">
    <input type="text" name="search" placeholder="Search post" value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form><br>
<a href="add.php">Add New Post</a>
<br><br><table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Content</th>
        <th>Image</th>
        <th>Action</th>
    </tr><?php if ($result && mysqli_num_rows($result) > 0) { ?>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['content']; ?></td>
            <td>
                <?php if (!empty($row['image'])) { ?>
                    <img src="uploads/<?php echo $row['image']; ?>">
                <?php } else { echo "No Image"; } ?>
            </td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete this post?')">Delete</a>
            </td>
        </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="5">No posts found</td>
    </tr>
<?php } ?>

</table></body>
</html>