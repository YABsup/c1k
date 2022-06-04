FROM registry.k-3soft.com/infrastructure/php-fpm:master

ADD /docker/www.conf /usr/local/etc/php-fpm.d/
ADD /docker/php.ini /usr/local/etc/php/
ADD /docker/supervisord.conf /etc/supervisor/
ADD /docker/*.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/*.sh 

COPY --chown=www-data:www-data . /var/www

CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]