#!/bin/bash

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"/../..

ln -fs $ROOT_DIR/project/config/production/nginx/dialogue.conf /etc/nginx/sites-enabled/dialogue
/usr/sbin/service nginx reload

/bin/bash $ROOT_DIR/project/tool/dep_build.sh link
/usr/bin/php $ROOT_DIR/public/cli.php migrate

ln -fs $ROOT_DIR/project/config/production/supervisor/dialogue_operator.conf /etc/supervisor/conf.d/dialogue_operator.conf
/usr/bin/supervisorctl update
/usr/bin/supervisorctl restart dialogue_operator:*
