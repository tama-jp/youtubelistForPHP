<?php
session_start();
require 'config.php'; // データベース接続

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ユーザーを検索
    $stmt = $database->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bindValue(1, $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    // パスワードの検証
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];  // 権限をセッションに保存
        $_SESSION['user_id'] = $user['id']; // ユーザーIDをセッションに保存
        header('Location: video_list.php');
        exit();
    } else {
        echo 'ログインに失敗しました';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
<form method="POST" action="login.php">
    <input type="text" name="username" placeholder="ユーザー名">
    <input type="password" name="password" placeholder="パスワード">
    <button type="submit">ログイン</button>
</form>
</body>
</html>