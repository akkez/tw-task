FROM php:7.0-cli

RUN apt-get update && apt-get install -y \
	mysql-client \
	&& docker-php-ext-install pdo_mysql

CMD ["php", "-S", "0.0.0.0:80", "-t", "/var/www/app/public", "/var/www/app/router.php"]