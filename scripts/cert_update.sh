#!/bin/bash
DOMAIN='api.betta.fortuna.jp'
CURRENT_YEAR=`date +%Y`
CURRENT_MONTH=`date +%m`
CURRENT_DAY=`date +%d`
cd /var/www/html/${DOMAIN}/scripts
. ./cert_install.sh
cd /var/www/html/${DOMAIN}
git reset HEAD
git add cert/${CURRENT_YEAR}/${CURRENT_MONTH}
git add etc/nginx.conf
git commit -m "chore: certficate was auto updated at ${CURRENT_YEAR}.${CURRENT_MONTH}.${CURRENT_DAY}"
git push
#sudo chown root /var/www/html/${DOMAIN}/scripts/cert_update.sh
#sudo chmod 755 /var/www/html/${DOMAIN}/scripts/cert_update.sh
#ln -nfs /var/www/html/${DOMAIN}/scripts/cert_update.sh /etc/cron.monthly/${DOMAIN}
