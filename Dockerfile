# ベースイメージとして公式のPHP FPMイメージを使用
FROM php:8.2-fpm

# 必要な拡張機能をインストール
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Composerをインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node.jsをインストール
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 作業ディレクトリを設定
WORKDIR /var/www

# アプリケーションファイルをコピー
COPY src/ /var/www

# 権限を設定
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www

# Laravelの依存関係をインストール
RUN composer install --prefer-dist --no-scripts --no-dev --optimize-autoloader

# Node.jsの依存関係をインストール
RUN npm install

# 設定ファイルをコピー
COPY .env /var/www/.env

# Laravelのアプリケーションキーを生成
RUN php artisan key:generate

# データベースのマイグレーションを実行
RUN php artisan migrate

# 開発サーバーを起動
CMD php-fpm
