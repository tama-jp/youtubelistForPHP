<?php
require 'header.php';  // メニューとユーザー情報の表示
require 'config.php';  // データベース接続

// 管理者のみアクセスを許可
if ($_SESSION['role'] != 'admin') {
    header('Location: user_list.php');
    exit();
}

// 編集対象のユーザーIDを取得
$user_id = $_GET['id'];

// ユーザー情報の取得
$stmt = $database->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bindValue(1, $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    echo "ユーザーが見つかりません。";
    exit();
}

// フォームが送信されたとき
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    // パスワードの変更が行われた場合のみハッシュ化して保存
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $database->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $stmt->bindValue(2, $password, SQLITE3_TEXT);
        $stmt->bindValue(3, $role, SQLITE3_TEXT);
        $stmt->bindValue(4, $user_id, SQLITE3_INTEGER);
    } else {
        $stmt = $database->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $stmt->bindValue(1, $username, SQLITE3_TEXT);
        $stmt->bindValue(2, $role, SQLITE3_TEXT);
        $stmt->bindValue(3, $user_id, SQLITE3_INTEGER);
    }

    $stmt->execute();

    echo "ユーザーが更新されました！";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー編集</title>
</head>
<body>
<h1>ユーザー編集</h1>
<form method="POST" action="edit_user.php?id=<?= $user_id ?>">
    <label for="username">ユーザー名:</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

    <label for="password">パスワード (変更する場合のみ入力):</label>
    <input type="password" name="password"><br>

    <label for="role">権限:</label>
    <select name="role" required>
        <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>一般ユーザー</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>管理者</option>
    </select><br>

    <button type="submit">更新</button>
</form>
</body>
</html>