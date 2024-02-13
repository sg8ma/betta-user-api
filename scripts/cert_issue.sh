#!/bin/bash
DOMAIN='api.betta.fortuna.jp'
PUBLIC_DIR='public'
/home/ec2-user/.acme.sh/acme.sh --issue \
    -d ${DOMAIN} \
    -w /var/www/html/${DOMAIN}/${PUBLIC_DIR} \
    --force
