#!/bin/bash
DOMAIN='api.betta.fortuna.jp'
CURRENT_YEAR=`date +%Y`
CURRENT_MONTH=`date +%m`
mkdir -p /var/www/html/${DOMAIN}/certs/${CURRENT_YEAR}/${CURRENT_MONTH}
/home/ec2-user/.acme.sh/acme.sh --install-cert \
    -d ${DOMAIN} \
    --key-file /var/www/html/${DOMAIN}/certs/${CURRENT_YEAR}/${CURRENT_MONTH}/privkey.pem \
    --fullchain-file /var/www/html/${DOMAIN}/certs/${CURRENT_YEAR}/${CURRENT_MONTH}/fullchain.pem
sed -i".bak" -e "s|/certs/[0-9]\{4\}/[0-9]\{2\}|/certs/${CURRENT_YEAR}/${CURRENT_MONTH}|g" /var/www/html/${DOMAIN}/etc/nginx.conf
~                                                                                                                                    
