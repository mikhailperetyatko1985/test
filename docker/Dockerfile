# Этап сборки vendor: зависимости ставятся при docker compose build,
# результат кладём в /opt/vendor (не в /app — его на рантайме перекрывает mount исходников).
FROM composer:2 AS vendor
WORKDIR /src
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress --no-scripts \
 && composer dump-autoload -o --no-scripts

FROM php:8.3-cli
WORKDIR /app
COPY . .
COPY --from=vendor /src/vendor /opt/vendor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
EXPOSE 8000
ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
