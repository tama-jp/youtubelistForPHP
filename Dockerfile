# PHP 8.0とApacheをベースにしたイメージ
FROM php:8.0-apache

# 必要なパッケージのインストール（SQLite関連パッケージを含む）
RUN apt-get update && apt-get install -y \
    sqlite3 \
    libsqlite3-dev \
    pkg-config

# SQLiteとPDOのインストール
RUN docker-php-ext-install pdo_sqlite

# 作業ディレクトリを設定
WORKDIR /var/www/html

# プロジェクトファイルをコンテナにコピー
COPY ./public/ /var/www/html/
COPY ./config.php /var/www/html/
COPY ./db/ /var/www/html/db/

# Apacheの設定を有効化
RUN a2enmod rewrite

# ポート80を公開
EXPOSE 80