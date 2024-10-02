<?php
ob_start();  // 出力バッファリングを開始

require 'header.php';  // メニューとユーザー情報の表示
require 'config.php';  // データベース接続

// ログイン確認
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// 動画の削除処理
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // 動画の削除
    $stmt = $database->prepare("DELETE FROM videos WHERE id = ?");
    $stmt->bindValue(1, $delete_id, SQLITE3_INTEGER);
    $stmt->execute();

    // 動画に関連するアクセス権の削除
    $stmt = $database->prepare("DELETE FROM video_permissions WHERE video_id = ?");
    $stmt->bindValue(1, $delete_id, SQLITE3_INTEGER);
    $stmt->execute();

    // 削除完了メッセージとリダイレクト
    echo "<script>alert('動画が削除されました！');</script>";
    header('Location: video_list.php');  // リダイレクトして一覧を再表示
    exit();  // リダイレクト後にコードを実行しないようにする
}

// ユーザーIDの取得
$user_id = $_SESSION['user_id'];

// ユーザーに許可された動画を取得
$stmt = $database->prepare("
    SELECT videos.id, videos.title 
    FROM videos 
    JOIN video_permissions ON videos.id = video_permissions.video_id 
    WHERE video_permissions.user_id = ?
");
$stmt->bindValue(1, $user_id, SQLITE3_INTEGER);
$result = $stmt->execute();

$videos = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $videos[] = $row;
}

ob_end_flush();  // 出力バッファを終了して内容を出力
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>動画一覧</title>
</head>
<body>
<h1>動画一覧</h1>
<ul>
    <?php foreach ($videos as $video): ?>
        <li>
            <a href="video.php?id=<?= $video['id'] ?>"><?= htmlspecialchars($video['title']) ?></a>
            <!-- 編集リンク -->
            <a href="edit_video.php?id=<?= $video['id'] ?>">編集</a>
            <!-- 削除リンク -->
            <a href="video_list.php?delete_id=<?= $video['id'] ?>" onclick="return confirm('本当にこの動画を削除しますか？')">削除</a>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>