<?php
require 'header.php';  // メニューとユーザー情報の表示
require 'config.php';  // データベース接続

// 管理者のみアクセスを許可
if ($_SESSION['role'] != 'admin') {
    header('Location: video_list.php');
    exit();
}

// ユーザーリストの取得
$users = $database->query("SELECT id, username FROM users");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $url = $_POST['url'];
    $visible_to = $_POST['visible_to'];  // どのユーザーに見せるかのフィールドを取得
    $permitted_users = $_POST['permitted_users'];  // 選択されたユーザーIDの配列

    // 新規動画の登録
    $stmt = $database->prepare("INSERT INTO videos (title, url, visible_to) VALUES (?, ?, ?)");
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $url, SQLITE3_TEXT);
    $stmt->bindValue(3, $visible_to, SQLITE3_TEXT);  // visible_toの値を保存
    $stmt->execute();

    $video_id = $database->lastInsertRowID();  // 挿入した動画のIDを取得

    // 許可されたユーザーを video_permissions に登録
    foreach ($permitted_users as $user_id) {
        $stmt = $database->prepare("INSERT INTO video_permissions (video_id, user_id) VALUES (?, ?)");
        $stmt->bindValue(1, $video_id, SQLITE3_INTEGER);
        $stmt->bindValue(2, $user_id, SQLITE3_INTEGER);
        $stmt->execute();
    }

    echo '新規動画が追加され、アクセス許可が設定されました！';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規動画追加</title>
</head>
<body>
<h1>新規動画追加</h1>
<form method="POST" action="add_video.php">
    <input type="text" name="title" placeholder="動画タイトル" required>
    <input type="text" name="url" placeholder="YouTube URL" required>

    <!-- visible_to フィールドを追加 -->
    <label for="visible_to">この動画を表示する対象:</label>
    <select name="visible_to" required>
        <option value="admin">管理者のみ</option>
        <option value="user">一般ユーザーのみ</option>
        <option value="both">両方</option>
    </select>

    <h3>この動画を閲覧できるユーザーを選択してください：</h3>
    <?php while ($user = $users->fetchArray(SQLITE3_ASSOC)): ?>
        <label>
            <input type="checkbox" name="permitted_users[]" value="<?= $user['id'] ?>">
            <?= htmlspecialchars($user['username']) ?>
        </label><br>
    <?php endwhile; ?>

    <button type="submit">動画追加</button>
</form>
</body>
</html>