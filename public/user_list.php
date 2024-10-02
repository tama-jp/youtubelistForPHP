<?php
require 'header.php';  // メニューとユーザー情報の表示
require 'config.php';  // データベース接続

// 管理者のみアクセスを許可
if ($_SESSION['role'] != 'admin') {
    header('Location: video_list.php');
    exit();
}

// ユーザーリストの取得
$users = $database->query("SELECT id, username, role FROM users");

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー一覧</title>
</head>
<body>
<h1>ユーザー一覧</h1>
<table border="1">
    <tr>
        <th>ユーザー名</th>
        <th>権限</th>
        <th>操作</th>
    </tr>
    <?php while ($user = $users->fetchArray(SQLITE3_ASSOC)): ?>
        <tr>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <!-- 編集リンクを追加 -->
                <a href="edit_user.php?id=<?= $user['id'] ?>">編集</a>
                <?php if ($user['role'] != 'admin'): ?>
                    | <a href="user_list.php?delete_id=<?= $user['id'] ?>" onclick="return confirm('本当に削除しますか？')">削除</a>
                <?php else: ?>
                    | 管理者
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
</body>
</html>