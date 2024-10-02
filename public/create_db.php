<?php
// SQLiteデータベースファイルの場所
$database = new SQLite3(__DIR__ . '/db/database.db');

// ユーザーテーブルの作成
$database->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL
)");

// 動画テーブルの作成に 'visible_to' カラムを追加
$database->exec("CREATE TABLE IF NOT EXISTS videos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    url TEXT NOT NULL,
    visible_to TEXT NOT NULL  -- 動画が誰に表示されるか
)");

// 動画アクセス許可テーブルの作成
$database->exec("CREATE TABLE IF NOT EXISTS video_permissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    video_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (video_id) REFERENCES videos(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)");


// 管理者と一般ユーザーの初期データ
$adminPassword = password_hash('adminpassword', PASSWORD_DEFAULT);
$userPassword = password_hash('userpassword', PASSWORD_DEFAULT);

// 初期ユーザーの追加
$database->exec("INSERT INTO users (username, password, role) VALUES ('admin', '$adminPassword', 'admin')");
$database->exec("INSERT INTO users (username, password, role) VALUES ('user', '$userPassword', 'user')");

echo "データベースが初期化されました。";
?>