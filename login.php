
    <?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        die("All fields required");
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {

            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['admin'];

            header("Location: posts.php");
            exit;

        } else {
            echo "Wrong password";
        }
    } else {
        echo "User not found";
    }
}
?>

<form method="post">
    <input name="username" placeholder="Username"><br><br>
    <input type="password" name="password" placeholder="Password"><br><br>
    <button name="login">Login</button>
</form>