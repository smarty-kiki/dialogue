<?php

ini_set('display_errors', 'on');
date_default_timezone_set('Asia/Shanghai');

define('ROOT_DIR', __DIR__);
define('FRAME_DIR', ROOT_DIR.'/frame');
define('COMMAND_DIR', ROOT_DIR.'/command');
define('CONTROLLER_DIR', ROOT_DIR.'/controller');
define('DIALOGUE_TOPIC_DIR', COMMAND_DIR.'/dialogue_topic');

include FRAME_DIR.'/function.php';
include FRAME_DIR.'/otherwise.php';
include FRAME_DIR.'/cache/redis.php';
include FRAME_DIR.'/database/mysql.php';
include FRAME_DIR.'/queue/beanstalk.php';
include FRAME_DIR.'/dialogue/beanstalk.php';
include FRAME_DIR.'/log/file.php';

config_dir(ROOT_DIR.'/config');

include ROOT_DIR.'/util/load.php';
include DIALOGUE_TOPIC_DIR.'/load.php';
