#!/bin/bash

ROOT_DIR="$(cd "$(dirname $0)" && pwd)"/../..

sh $ROOT_DIR/project/tool/dep_build.sh link

sudo docker run --rm -ti -p 80:80 -p 8080:8080 -p 3306:3306 --name dialogue \
    -v $ROOT_DIR/../frame:/var/www/frame \
    -v $ROOT_DIR/:/var/www/dialogue \
    -v $ROOT_DIR/project/config/development/nginx/dialogue.conf:/etc/nginx/sites-enabled/default \
    -v $ROOT_DIR/project/config/development/supervisor/dialogue_operator.conf:/etc/supervisor/conf.d/dialogue_operator.conf \
    -e 'TIMEZONE=Asia/Shanghai' \
kikiyao/debian_php_dev_env start
