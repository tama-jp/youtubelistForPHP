<?php
require 'header.php';  // メニューとユーザー情報の表示
require 'config.php';  // データベース接続

// 管理者のみアクセスを許可
if ($_SESSION['role'] != 'admin') {
    header('Location: video_list.php');
    exit();
}

// 編集対象の動画IDを取得
$video_id = $_GET['id'];

// 動画情報の取得
$stmt = $database->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->bindValue(1, $video_id, SQLITE3_INTEGER);
$result = $stmt->execute();
$video = $result->fetchArray(SQLITE3_ASSOC);

if (!$video) {
    echo "動画が見つかりません。";
    exit();
}

// フォームが送信されたとき
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $url = $_POST['url'];
    $visible_to = $_POST['visible_to'];  // 動画の表示対象

    // 動画情報の更新
    $stmt = $database->prepare("UPDATE videos SET title = ?, url = ?, visible_to = ? WHERE id = ?");
    $stmt->bindValue(1, $title, SQLITE3_TEXT);
    $stmt->bindValue(2, $url, SQLITE3_TEXT);
    $stmt->bindValue(3, $visible_to, SQLITE3_TEXT);
    $stmt->bindValue(4, $video_id, SQLITE3_INTEGER);
    $stmt->execute();

    echo "動画が更新されました！";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>動画編集</title>
</head>
<body>
<h1>動画編集</h1>
<form method="POST" action="edit_video.php?id=<?= $video_id ?>">
    <label for="title">タイトル:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($video['title']) ?>" required><br>

    <label for="url">YouTube URL:</label>
    <input type="text" name="url" value="<?= htmlspecialchars($video['url']) ?>" required><br>

    <label for="visible_to">表示対象:</label>
    <select name="visible_to" required>
        <option value="admin" <?= $video['visible_to'] == 'admin' ? 'selected' : '' ?>>管理者のみ</option>
        <option value="user" <?= $video['visible_to'] == 'user' ? 'selected' : '' ?>>一般ユーザーのみ</option>
        <option value="both" <?= $video['visible_to'] == 'both' ? 'selected' : '' ?>>両方</option>
    </select><br>

    <button type="submit">更新</button>
</form>
</body>
</html>