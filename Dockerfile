FROM php:7.2-fpm

# Instala extensões necessárias para Laravel
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libonig-dev \
    && docker-php-ext-install pdo_mysql

# Instala o Composer diretamente no contêiner
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    php -r "unlink('composer-setup.php');"

# Define o diretório de trabalho dentro do contêiner
WORKDIR /var/www

# Copia os arquivos do Laravel para o contêiner
COPY . .

# Garante que o arquivo .env seja gerado com base no .env.example
RUN [ ! -f .env ] && cp .env.example .env || echo ".env já existe"

# Instala as dependências do Composer e configura o Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajusta as permissões para as pastas essenciais
RUN chmod -R 775 storage bootstrap/cache

# Exponha a porta usada pelo Laravel
EXPOSE 8000

# Comando para rodar as migrações e seeds automaticamente antes de iniciar o servidor
CMD bash -c "php artisan migrate --force --seed && php artisan passport:install --force && php artisan serve --host=0.0.0.0 --port=8000"