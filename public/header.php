<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header('Location: login.php');
    exit();
}

// ログインしているユーザーの名前を表示
$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>メニュー</title>
</head>
<body>
<header>
    <h2>ようこそ、<?= htmlspecialchars($username) ?>さん</h2>
    <nav>
        <ul>
            <li><a href="video_list.php">動画一覧</a></li>
            <?php if ($role == 'admin'): ?>
                <li><a href="add_video.php">動画追加</a></li>
                <li><a href="add_user.php">ユーザー追加</a></li>
                <li><a href="user_list.php">ユーザー一覧</a></li>
            <?php endif; ?>
            <li><a href="logout.php">ログアウト</a></li>
        </ul>
    </nav>
</header>
</body>
</html>