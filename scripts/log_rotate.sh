/var/www/html/api.betta.fortuna.jp/logs/access.log {
    rotate 90
    daily
    dateext
    dateformat .%m%d
    missingok
    ifempty
    postrotate
        mv /var/www/html/api.betta.fortuna.jp/logs/access.log.`date '+%m%d'` /var/www/html/api.betta.fortuna.jp/logs/`date '+%m%d'`_access.log
    endscript
}

/var/www/html/api.betta.fortuna.jp/logs/error.log {
    rotate 30
    daily
    dateext
    dateformat .%m%d
    missingok
    ifempty
    postrotate
        mv /var/www/html/api.betta.fortuna.jp/logs/error.log.`date '+%m%d'` /var/www/html/api.betta.fortuna.jp/logs/`date '+%m%d'`_error.log
    endscript
}

#sudo chown root /var/www/html/${DOMAIN}/scripts/log_rotate.sh
#sudo chmod 755 /var/www/html/${DOMAIN}/scripts/log_rotate.sh
#ln -nfs /var/www/html/${DOMAIN}/scripts/log_rotate.sh /etc/cron.monthly/log_${DOMAIN}

