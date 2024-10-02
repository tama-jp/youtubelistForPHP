<?php
require 'header.php';  // メニューとユーザー情報の表示
require 'config.php';  // データベース接続

// 管理者のみアクセスを許可
if ($_SESSION['role'] != 'admin') {
    header('Location: video_list.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // パスワードのハッシュ化
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 新規ユーザーの登録
    $stmt = $database->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $stmt->bindValue(2, $hashed_password, SQLITE3_TEXT);
    $stmt->bindValue(3, $role, SQLITE3_TEXT);
    $stmt->execute();

    echo '新規ユーザーが追加されました！';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規ユーザー追加</title>
</head>
<body>
<h1>新規ユーザー追加</h1>
<form method="POST" action="add_user.php">
    <input type="text" name="username" placeholder="ユーザー名" required>
    <input type="password" name="password" placeholder="パスワード" required>
    <select name="role" required>
        <option value="user">一般ユーザー</option>
        <option value="admin">管理者</option>
    </select>
    <button type="submit">ユーザー追加</button>
</form>
</body>
</html>