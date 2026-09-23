# Этап сборки vendor: зависимости ставятся при docker compose build,
# результат кладём в /opt/vendor (не в /app — его на рантайме перекрывает mount исходников).
FROM composer:2 AS vendor
# Версия PHP внутри образа composer не важна для сборки зависимостей —
# игнорируем только требование php, остальные платформенные проверки остаются.
ENV COMPOSER_IGNORE_PLATFORM_REQ=php
WORKDIR /src
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts \
 && composer dump-autoload -o --no-scripts

FROM php:8.3-cli
WORKDIR /app
# Расширение memcached нужно Laravel для тегированного кеша (CACHE_STORE=memcached).
# zlib1g-dev обязателен: configure расширения memcached требует ZLIB.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libmemcached-dev zlib1g-dev \
    && pecl install memcached-3.4.0 \
    && docker-php-ext-enable memcached \
    && rm -rf /var/lib/apt/lists/*
COPY . .
COPY --from=vendor /src/vendor /opt/vendor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
EXPOSE 8000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
